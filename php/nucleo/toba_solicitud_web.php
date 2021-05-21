<?php
/**
 * Solicitud pensada para contener el ciclo request-response http
 * La etapa de request se la denomina de 'eventos' 
 * La etapa de response se la denomina de 'servicios'
 * 
 * @package Centrales
 * 
 * @todo Al servicio pdf le falta pedir por parametro que metodo llamar para construirlo
 */
class toba_solicitud_web extends toba_solicitud
{
	protected $zona;			//Objeto que representa una zona que vincula varios items
	protected $cis;
	protected $cn;
	protected $tipo_pagina;
	protected $autocomplete = true;
	
	function __construct($info)
	{
		$this->info = $info;
		if (toba_editor::activado()) {
			toba_editor::set_item_solicitado(toba::memoria()->get_item_solicitado());
		}
		parent::__construct(toba::memoria()->get_item_solicitado(), toba::usuario()->get_id());
	}

	/**
	 * Crea la zona, carga los componentes, procesa los eventos y los servicios
	 */
	function procesar()
	{		
		$en_mantenimiento = (toba::proyecto()->get_parametro('proyecto', 'modo_mantenimiento', false) == 1) ;
		if ($en_mantenimiento) {
			$this->pre_proceso_servicio();	//Saca css y no queda alert pelado
			$msg = toba::proyecto()->get_parametro('proyecto', 'mantenimiento_mensaje');
			toba::notificacion()->info($msg);
			toba::notificacion()->mostrar();
		} else {
			try {
				$this->crear_zona();
				$retrasar_headers = ($this->info['basica']['retrasar_headers']);
				//Chequeo si necesito enviar la clase para google analytics (necesito ponerlo aca para que salga como basico)
				//La otra forma es agregarlo a la generacion de consumos globales al medio de la generacion de HTML
				if ($this->hacer_seguimiento() && ! $this->es_item_login()) {
					toba_js::agregar_consumos_basicos(array('basicos/google_analytics'));
				}
				
				// Si la pagina retrasa el envio de headers, no mando los pre_servicios ahora
				if (! $retrasar_headers) {
					$this->pre_proceso_servicio();
				}
				$this->cargar_objetos();
				toba::cronometro()->marcar('Procesando Eventos');
				$this->procesar_eventos();
				if ($retrasar_headers) {
					$this->pre_proceso_servicio();
				}
				toba::cronometro()->marcar('Procesando Servicio');
				$this->procesar_servicios();
			}catch(toba_error $e) {
				toba::logger()->error($e, 'toba');
				$mensaje_debug = null;
				if (toba::logger()->modo_debug()) {
					$mensaje_debug = $e->get_mensaje_log();
				}				
				toba::notificacion()->error($e->get_mensaje(), $mensaje_debug);
				toba::notificacion()->mostrar();
			}
		}
	}
	
	/**
	 * Permite que el servicio produzca alguna salida antes de los eventos, para optimizaciones
	 */
	protected function pre_proceso_servicio()
	{
		$servicio = toba::memoria()->get_servicio_solicitado();
		$callback = "servicio_pre__$servicio";
		if (method_exists($this, $callback)) {
			$this->$callback();
		}
	}
	
	/**
	 * Instancia los cis/cns de primer nivel asignados al item y los relaciona
	 */
	protected function cargar_objetos()
	{
		
		toba::logger()->seccion("Iniciando componentes...", 'toba');
		$this->cis = array();		
		if (count($this->info['objetos']) > 0) {
			if (toba::proyecto()->get_parametro('navegacion_ajax')) {
				toba_ci::set_navegacion_ajax(true);
			}
			$i = 0;
			//Construye los objetos ci y el cn
			foreach ($this->info['objetos'] as $objeto) {
				if ($objeto['clase'] != 'toba_cn') {
					$this->cis[] = $this->cargar_objeto($objeto['clase'],$i); 
					$i++;
				} else {
					$this->cn = $this->cargar_objeto($objeto['clase'],0); 
					$this->objetos[$this->cn]->inicializar();
				}
			}
			//Asigna el cn a los cis			
			if (isset($this->cn)) {			
				foreach ($this->cis as $ci) {
					$this->objetos[$ci]->asignar_controlador_negocio( $this->objetos[$this->cn] );
			    } 
			}
		} else { 
			if ($this->info['basica']['item_act_accion_script'] == '') {
				throw new toba_error_def("Necesita asociar un objeto CI al ítem.");
			}
	    }
	}

	/**
	 * Inicializa los componentes y dispara la atención de eventos en forma recursiva
	 */
	protected function procesar_eventos()
	{
		//--Antes de procesar los eventos toda entrada UTF-8 debe ser pasada a ISO88591
		if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'UTF-8') !== false) {
			$_POST = array_a_latin1($_POST);
		}
		//-- Se procesan los eventos generados en el pedido anterior
		foreach ($this->cis as $ci) {
			$this->objetos[$ci]->inicializar();
			try {
				toba::logger()->seccion("Procesando eventos...", 'toba');
				$this->objetos[$ci]->disparar_eventos();
			} catch (toba_error $e) {
				toba::logger()->error($e, 'toba');
				$mensaje_debug = null;
				if (toba::logger()->modo_debug()) {
					$mensaje_debug = $e->get_mensaje_log();
				}				
				toba::notificacion()->error($e->get_mensaje(), $mensaje_debug);
				toba::notificacion()->set_titulo($e->get_titulo_ventana());
			}
		}
	}
	
	/**
	 * Se configuran los componentes y se atiende el servicio en forma recursiva
	 */
	protected function procesar_servicios()
	{
		toba::logger()->seccion("Configurando dependencias para responder al servicio...", 'toba');
		//Se fuerza a que los Cis carguen sus dependencias (por si alguna no se cargo para atender ls eventos) y se configuren
		foreach ($this->cis as $ci) {
			$this->objetos[$ci]->pre_configurar();
			//--- Lugar para poner algun callback a nivel operacion?
			$this->objetos[$ci]->post_configurar();
		}
		//--- Si existe la zona se brinda la posibilidad de configurarla
		if ($this->hay_zona() && toba::zona()->cargada()) {
				toba::zona()->conf();
		}		
		//--- Se determina el destino del servicio
		$objetos_destino = toba::memoria()->get_id_objetos_destino();
		if (!isset($objetos_destino)) {
			//En caso que el servicio no especifique destino, se asume que son todos los cis asociados al item
			$destino = array();
			foreach ($this->cis as $ci) {
				$destino[] = $this->objetos[$ci];	
			}
		} else {
			//Se especifico un destino, se buscan los objetos referenciados
			$destino = array();
			foreach ($objetos_destino as $obj) {
				$destino[] = toba_constructor::buscar_runtime(array('proyecto' => $obj[0],
																'componente' => $obj[1]));
			}
		}

		$servicio = toba::memoria()->get_servicio_solicitado();
		$callback = "servicio__$servicio";
		toba::logger()->seccion("Respondiendo al $callback...", 'toba');		
		if (method_exists($this, $callback)) {
			$this->$callback($destino);
		} elseif (count($destino) == 1 && method_exists(current($destino), $callback)) {
			//-- Se pide un servicio desconocido para la solicitud, pero conocido para el objeto destino
			$obj = current($destino);
			$obj->$callback();
		} else {
			throw new toba_error_seguridad("El servicio $servicio no está soportado");			
		}
	}

	//---------------------------------------------------------------
	//-------------------------- SERVICIOS --------------------------
	//---------------------------------------------------------------

	/**
	 * Optimización del servicio de generar html para enviar algun contenido al browser
	 */
	protected function servicio_pre__generar_html()
	{
		$this->tipo_pagina()->encabezado();
	}
	
	
	protected function generar_html_botonera_sup($objetos)
	{
		//--- Se incluyen botones en la botonera de la operacion
		foreach ($objetos as $obj) {	
			if (is_a($obj, 'toba_ci')) {
				if ($obj->es_botonera_en_barra_item()) {
					$obj->pantalla()->generar_botones('enc-botonera');					
				}
			}
			$this->generar_html_botonera_sup($obj->get_dependencias());
		}		
	}
	
	/**
	 * Servicio común de generación html
	 */
	protected function servicio__generar_html($objetos)
	{
		//--- Parte superior de la zona
		if (toba::solicitud()->hay_zona() && toba::zona()->cargada()) {
			toba::zona()->generar_html_barra_superior();
		}
		//--- Se incluyen botones en la botonera de la operacion
		$this->generar_html_botonera_sup($objetos);
		echo "</div>";//---- Se finaliza aqui el div de la barra superior
		echo '<div style="clear:both;"></div>';
		echo "</div>"; //-- Se finaliza aqui el div del encabezado, por la optimizacion del pre-servicio..
		$this->tipo_pagina()->pre_contenido();
		
		//--- Abre el formulario
		$accion = $this->info['basica']['item_act_accion_script'];
		if ($accion == '') {
			$extra = "onsubmit='return false;'";
			if (! $this->autocomplete) {
				$extra .= " autocomplete='off'";
			}
			echo toba_form::abrir("formulario_toba", toba::vinculador()->get_url(), $extra);
			
			//HTML
			foreach ($objetos as $obj) {
				//-- Librerias JS necesarias
				toba_js::cargar_consumos_globales($obj->get_consumo_javascript());
				//-- HTML propio del objeto
				$obj->generar_html();
			}
			
			//Javascript
			echo toba_js::abrir();
			try {
				toba_js::cargar_definiciones_runtime();
				foreach ($objetos as $obj) {
					$objeto_js = $obj->generar_js();
					echo "\n$objeto_js.iniciar();\n";
				}
			} catch (toba_error $e) {
				toba::logger()->error($e, 'toba');
				$mensaje_debug = null;
				if (toba::logger()->modo_debug()) {
					$mensaje_debug = $e->get_mensaje_log();
				}						
				toba::notificacion()->error($e->get_mensaje(), $mensaje_debug);
			}
			echo toba_js::cerrar();		
				
			//--- Fin del form y parte inferior del tipo de página
			echo toba_form::cerrar();
		} else {
			echo toba_js::abrir();
			toba_js::cargar_definiciones_runtime();
			echo toba_js::cerrar();
			include($accion);	
		}

		$this->tipo_pagina()->post_contenido();
		// Carga de componentes JS genericos
		echo toba_js::abrir();
		toba::vinculador()->generar_js();
		toba::notificacion()->mostrar(false);
		toba::acciones_js()->generar_js();
		$this->generar_analizador_estadistico();
		echo toba_js::cerrar();
		
		//--- Parte inferior de la zona
		if ($this->hay_zona() &&  $this->zona->cargada()) {
			$this->zona->generar_html_barra_inferior();
		}
       	$this->tipo_pagina()->pie();
	}
	
	/**
	 * Genera una salida en formato pdf
	 */
	
	protected function servicio__vista_pdf( $objetos )
	{
		$salida = new toba_vista_pdf();
		$salida->asignar_objetos( $objetos );
		$salida->generar_salida();
		$salida->enviar_archivo();
	}
	
	protected function servicio__vista_xml( $objetos )
	{
		$salida = new toba_vista_xml();
		$salida->asignar_objetos( $objetos );
		$salida->generar_salida();
		$salida->enviar_archivo();
	}

	protected function servicio__vista_xslfo( $objetos )
	{
		$salida = new toba_vista_xslfo();
		$salida->asignar_objetos( $objetos );
		$salida->generar_salida();
		$salida->enviar_archivo();
	}
	
	protected function servicio__vista_jasperreports( $objetos )
	{
		$salida = new toba_vista_jasperreports();
		$salida->asignar_objetos($objetos);
		$salida->generar_salida();
		$salida->enviar_archivo();
	}

	protected function servicio__enviar_por_mail( $objetos )
	{
		$salida = new toba_vista_jasperreports();
		toba::logger()->info("PASA MAIL 0");
		//toba::logger()->info($objetos);
		$salida->asignar_objetos($objetos);
		toba::logger()->info("PASA MAIL 1");
		$salida->generar_salida();
		toba::logger()->info("PASA MAIL 2");
		// 

		ei_arbol(toba::memoria()->get_parametros());
		//$salida->enviar_archivo();
	}

	function strpos_r($haystack, $needle) {
	    if(strlen($needle) > strlen($haystack))
	        trigger_error(sprintf("%s: length of argument 2 must be <= argument 1", __FUNCTION__), E_USER_WARNING);

	    $seeks = array();
	    while($seek = strrpos($haystack, $needle))
	    {
	        array_push($seeks, $seek);
	        $haystack = substr($haystack, 0, $seek);
	    }
	    return $seeks;
	}

	function escanear_parametros($reporte, $query){
		$ini= $this->strpos_r($query, "[");
		$fin= $this->strpos_r($query, "]");
		$parametros= array();
		$parametros2= array();
		for ($i=0; $i < count($ini); $i++) { 
			$param= substr($query, $ini[$i], $fin[$i] - $ini[$i] + 1);
			if (!in_array($param, $parametros2)){
				$filtro= array("reporte" => $reporte, "parametro" => $param);
				$tabla_parametros= dao_consultas_dinamicas::get_parametros_reportes($filtro);
				if (!in_array($param, $parametros2)) {
					if (isset($tabla_parametros[0])&&(!empty($tabla_parametros[0])))
						if (!isset($parametros2[$tabla_parametros[0]['orden']]))
							$parametros2[$tabla_parametros[0]['orden']]= $tabla_parametros[0]['parametro'];
						else
							array_push($parametros2, $tabla_parametros[0]['parametro']);
					else
						array_push($parametros2, $param);
				}
			}
			/*$parametros= array();
			foreach ($parametros2 as $key => $value) {
				array_unshift($parametros, $value);
			}*/
		}

		if (isset($parametros2[0]))
			foreach ($parametros2 as $key => $value) {
				$parametros3[$key+1]= $value;
			}
		else
			$parametros3= $parametros2;

		toba::logger()->info('--------------Orden Parametros--------------------');
		toba::logger()->info($parametros3);
		toba::logger()->info('--------------Orden Parametros--------------------');

		return $parametros3;
	}

	function reemplazar_parametros($query, $parametros){

		for ($i=0; $i < count($parametros); $i++) {
			$query= str_replace($parametros[$i]['parametro'], $parametros[$i]['valor'], $query);
		}

		return $query;
	}

	function escanear_prompts($query){
		$ini= $this->strpos_r($query, "{");
		$fin= $this->strpos_r($query, "}");
		$prompts= array();
		for ($i=0; $i < count($ini); $i++) { 
			$param= substr($query, $ini[$i], $fin[$i] - $ini[$i] + 1);

			if (!isset($prompts[$param]))
				$prompts[$param]= 1;
			else
				$prompts[$param]= $prompts[$param] + 1;
		}

		return $prompts;
	}

	function ajustar_campos_a_ancho($query, $ancho){
		$aux= $this->escanear_prompts($query);
		
		$total= 0;
		$cant= 0;
		foreach ($aux as $key => $value) {
			$val= substr($key, strpos($key, ",")+1, strpos($key, "}") - strpos($key, ",")-1);
			$cant+= $aux[$key];
			$total= $total + ($aux[$key]*$val);
		}
		
		$off= $total - $ancho;
		if ($off > 0) {
			foreach ($aux as $key => $value) {
				$prop= round($aux[$key]*100/$cant,2);
				$rest= round(($prop*$off/100)/$aux[$key],2);
				$val= substr($key, strpos($key, ",")+1, strpos($key, "}") - strpos($key, ",")-1);
				$val= $val - $rest;
				$campos= explode(",", $key);
				$campo= $key;
				if (substr_count($campos[1], ',') == 1){
					$campo= str_replace($campos[1], "$val}", $campo);
				}else{
					$campo= str_replace($campos[1], "$val", $campo);
				}

				$query= str_replace($key, $campo, $query);
			}
		}

		return $query;
	}

	protected function armar_encabezado_consulta_pdf($pdf, $titulo, $altura_titulo = 20, $parametros_mostrar= ''){
		//Encabezado: Logo Organización - Nombre
		$ancho= $pdf->ez['pageWidth'];
		$rigth= $pdf->ez['rightMargin'];
		$left= $pdf->ez['leftMargin'];
		//Recorremos cada una de las hojas del documento para agregar el encabezado
		foreach ($pdf->ezPages as $pageNum=>$id){
			$pdf->reopenObject($id);
			//definimos el path a la imagen de logo de la organizacion
			$imagen = toba::proyecto()->get_path().'/www/img/logo.jpg';
			//agregamos al documento la imagen y definimos su posición a través de las coordenadas (x,y) y el ancho y el alto.
			$pdf->addJpegFromFile($imagen, $left + 5, 750, 60, 60);
			//$pdf->rectangle(10,760, 550, 80);

			$pdf->setLineStyle(5,'round');
			/*$pdf->line(10, 835, 580, 835); //  -----
			$pdf->line(10, 835, 10, 815);  // |
			$pdf->line(580, 835, 580, 815); //     |
			$pdf->line(10, 815, 580, 815); // _____
			
			$pdf->setLineStyle(5,'round');
			//$pdf->line(10, 810, 580, 810); //  -----
			$pdf->line(10, 835, 10, 745);  // |
			$pdf->line(580, 835, 580, 745); //     |
			$pdf->line(10, 745, 580, 745); // _____
			*/

			$pdf->line($left, 835, $ancho - $rigth, 835); //  -----
			$pdf->line($left, 835, $left, 815);  // |
			$pdf->line($ancho - $rigth, 835, $ancho - $rigth, 815); //     |
			$pdf->line($left, 815, $ancho - $rigth, 815); // _____
			
			$pdf->setLineStyle(5,'round');
			//$pdf->line(10, 810, 580, 810); //  -----
			$pdf->line($left, 835, $left, 745);  // |
			$pdf->line($ancho - $rigth, 835, $ancho - $rigth, 745); //     |
			$pdf->line($left, 745, $ancho - $rigth, 745); // _____
			// 270 es la mitad
			// 25 es el inicio izquierdo
			// 565 es el fin derecho

			$x= 270 - (str_word_count($titulo)/2)*30;
			$pdf->addText($x,790,$altura_titulo, $titulo);
			$pdf->addText(500,750,8,date('d/m/Y'));
			//Agregamos el nombre de la organización a la cabecera junto al logo
			$bases_ini = toba_dba::get_bases_definidas();
			$usuario= toba::usuario()->get_id();
			$sistema= "";
			if (isset($bases_ini['reportes jasper']['p_municipio'])){
				$municipio=$bases_ini['reportes jasper']['p_municipio'];
				$sistema=$bases_ini['reportes jasper']['p_sistema'];
			}
			else
				$municipio= dao_varios::valor_configuraciones('NOMBRE_MUNICIPIO');
			$pdf->addText($left + 10,820,12,$sistema);
			$pdf->addText($left + 100,820,12,$municipio);
			$desde= $ancho - $rigth - 50 - count("Generado por $usuario");
			$pdf->addText(450,760,12, "Generado por $usuario");

			//agregar los parametros con que se ejecuto la consulta
			$pdf->addText($left + 75,752,6,$parametros_mostrar);

			$pdf->closeObject();
		}
	}

	protected function devolver_archivo($tipo_descarga, $type, $nombre_archivo){
		header("Cache-Control: private");
  		header("Content-type: $type");
  		header("Content-Length: ".filesize($nombre_archivo));
   		header("Content-Disposition: $tipo_descarga; filename=$nombre_archivo");
  		header("Pragma: no-cache");
		header("Expires: 0");

		readfile($nombre_archivo);
		unlink($nombre_archivo);
	}

	protected function servicio__generar_consulta_delimitado($query, $delimitador)
	{
		$sql= $query;
		$pdo= toba::db()->get_pdo();
		$handle= fopen('consulta.txt', 'w');
		try {
			$stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt->execute();
			$linea;
			$titulos;
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
			  unset($linea);

			  if (empty($titulos)) {
			  	foreach ($row as $key => $value) {
			  		if (empty($titulos))
						$titulos= substr($key, 0, strpos($key, "{") - 1);
					else
						$titulos= $titulos.$delimitador.substr($key, 0, strpos($key, "{") - 1);
				}
				fwrite($handle, $titulos. "\r\n");
			  }

			  foreach ($row as $key => $value) {
			  	if (!isset($linea)||empty($linea)) {
			  		$linea= $row[$key];
			  	}else{
			  		$linea= $linea.$delimitador.$row[$key];
			  	}
			  }
			  fwrite($handle, $linea. "\r\n");
			}
			$stmt = null;
		} catch (PDOException $e) {
			toba::logger()->error($e->getMessage());
			$toba::db()->cortar_sql($sql);
		}

		fclose($handle);

		return $this->devolver_archivo("attachment", "text/html", 'consulta.txt');
	}

	protected function servicio__generar_consulta_excel($objetos, $query)
	{
		$salida = new toba_vista_excel();
		//$salida->asignar_objetos( $objetos );
		$salida->set_nombre_archivo("consulta.xls");
		$salida->set_tipo_salida('Excel5');

		$sql= $query;
		$pdo= toba::db()->get_pdo();
		$titulos= array();
		$datos_salida= array();
		$agrupamiento= array();
		$corte= array();
		try {
			$stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {

			  if (empty($titulos)) {
			  	foreach ($row as $key => $value) {
					$titulo= substr($key, 0, strpos($key, "{") - 1);
					array_push($titulos, $titulo);
					$nivel_agrupamiento= null;
					if (substr_count($key, ',') == 2){
						$aux= $this->strpos_r($key, ',');
						$nivel_agrupamiento= substr($key,  $aux[0]+1, strpos($key, "}") - $aux[0] - 1);
					}

					if ($nivel_agrupamiento != null) {
						$agrupamiento[$nivel_agrupamiento]= $key;
					}

				}
			  }

			  //controlar el corte de control
			  if (count($agrupamiento) > 0) {
			  	for ($i=1; $i <= count($agrupamiento); $i++) { 
			  		if (!isset($control[$i])||$control[$i] != $row[$agrupamiento[$i]]) {
			  			if (($i+1 <= count($agrupamiento))&&(isset($control[$i+1]))) {
				  			for ($j=$i+1; $j < count($control); $j++) { 
				  				unset($control[$j]);
				  			}
			  			}
			  			$valor= $row[$agrupamiento[$i]];
			  			$control[$i]= $valor;
			  		}elseif ($control[$i] == $row[$agrupamiento[$i]]) {
			  			$row[$agrupamiento[$i]]= '';
			  		}
			  	}
			  }
			  
			  array_push($datos_salida, $row);
			}
			$stmt = null;
		} catch (PDOException $e) {
			toba::logger()->error($e->getMessage());
			$toba::db()->cortar_sql($sql);
		}
		
		$salida->tabla($datos_salida, $titulos);
		$salida->generar_salida();
		$salida->enviar_archivo();
	}

	 protected function rgbToCMYK( $rgbArray )
    {
        $cya = 1 - min( 1, max( (float)$rgbArray['r'], 0 ) );
        $mag = 1 - min( 1, max( (float)$rgbArray['g'], 0 ) );
        $yel = 1 - min( 1, max( (float)$rgbArray['b'], 0 ) );

        $min = min( $cya, $mag, $yel );
        if ( 1 - $min == 0 )
        {
            return array( 'c' => 1,
                          'm' => 1,
                          'y' => 1,
                          'k' => 0 );
        }

        return array( 'c' => ( $cya - $min ) / ( 1 - $min ),
                      'm' => ( $mag - $min ) / ( 1 - $min ),
                      'y' => ( $yel - $min ) / ( 1 - $min ),
                      'k' => $min );
    }

    protected function rgbToCMYK2( $r, $g, $b )
    {
        return $this->rgbToCMYK( array( 'r' => $r,
                                         'g' => $g,
                                         'b' => $b ) );
    }

	protected function servicio__generar_consulta_pdf($objetos, $query, $titulo, $altura_titulo, $parametros_mostrar= '')
	{
		/*$salida = new toba_vista_pdf();
		$salida->asignar_objetos( $objetos );
		toba::logger()->info("PASA CONSULTA 1");*/

		//////////////////////////////////////////////////

		$salida= new toba_vista_pdf();
		$nombre= 'consulta.pdf';

		$salida->set_nombre_archivo($nombre);

		$pdf = $salida->get_pdf();
		//modificamos los márgenes de la hoja top, bottom, left, right
		$pdf->ezSetMargins(105, 30, 20, 20);
		//Configuramos el pie de página. El mismo, tendra el número de página centrado en la página y la fecha ubicada a la derecha.
		//Primero definimos la plantilla para el número de página.
		$formato = 'Página {PAGENUM} de {TOTALPAGENUM}';
		//Determinamos la ubicación del número página en el pié de pagina definiendo las coordenadas x y, tamaño de letra, posición, texto, pagina inicio
		$pdf->ezStartPageNumbers(300, 20, 8, 'left', utf8_d_seguro($formato), 1);
		//Luego definimos la ubicación de la fecha en el pie de página.
		$pdf->addText(480,20,8,date('d/m/Y h:i:s a'));
		//Configuración de Título.
		//$salida->titulo(utf8_d_seguro("Consulta"));

		//////////////////////////////////////////////////
		//controlar que el ancho total de las columnas no pase el ancho visible
		$ancho= $salida->get_ancho(100);

		$query= $this->ajustar_campos_a_ancho($query, $ancho);
		toba::logger()->info('--------------Query Consulta Dinamica--------------------');
		toba::logger()->info($query);
		toba::logger()->info('--------------Query Consulta Dinamica--------------------');

		//////////////////////////////////////////////////

		$sql= $query;
		$pdo= toba::db()->get_pdo();
		$datos= array();
		$titulos= array();
		$options= array();
		$muestra_nombre_columnas= false;
		$i= 0;
		$datos= array();
		$agrupamiento= array();
		$corte= array();
		$sumar= array();
		$sumatoria= array();
		$sumatoria_total= array();
		$subtotal= false;
		$cant_sub_tot= 0;
		try {
			$stmt = $pdo->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT)) {
			  $row;
			  $i++;
			  if (empty($titulos)) {
			  	$muestra_nombre_columnas= true;
			  	foreach ($row as $key => $value) {
					$titulos[$key]= substr($key, 0, strpos($key, "{") - 1);
					$justification= substr($key, strpos($key, "{") + 1, strpos($key, ",")  - strpos($key, "{")- 1);
					$nivel_agrupamiento= null;
					if (substr_count($key, ',') == 1){
						$width= substr($key, strpos($key, ",") + 1, strpos($key, "}") - strpos($key, ",") - 1);
					}else{
						$aux= $this->strpos_r($key, ',');
						$width= substr($key, $aux[1]+1, $aux[0] - $aux[1] - 1);
						$nivel_agrupamiento= substr($key,  $aux[0]+1, strpos($key, "}") - $aux[0] - 1);
					}

					if (($nivel_agrupamiento != null)&&($nivel_agrupamiento != 'sum')) {
						$agrupamiento[$nivel_agrupamiento]= $key;
					}else{ //tiene como valor una suma o algun otro operador
						if (($nivel_agrupamiento != null)&&($nivel_agrupamiento == 'sum')) {
							toba::logger()->info('*****************************************');
						toba::logger()->info($nivel_agrupamiento);
						toba::logger()->info('*****************************************');
							array_push($sumar, $key);
						}
					}

					$options[$key]= array("justification" => $justification, "width" => $width);
				}
			  }

			  //controlar el corte de control
			  if (count($agrupamiento) > 0) {
			  	for ($i=1; $i <= count($agrupamiento); $i++) {
			  		if (!isset($control[$i])||$control[$i] != $row[$agrupamiento[$i]]) {
			  			if (($i+1 <= count($agrupamiento))&&(isset($control[$i+1]))) {
				  			for ($j=$i+1; $j <= count($control)+1; $j++) { 
				  				unset($control[$j]);
				  			}
			  			}
			  			if (isset($control[$i])){
			  				$subtotal= true;
			  			}
			  			$valor= $row[$agrupamiento[$i]];
			  			$control[$i]= $valor;
			  		}elseif ((isset($control[$i]))&&($control[$i] == $row[$agrupamiento[$i]])) {
			  			$row[$agrupamiento[$i]]= '';
			  		}
			  	}

			  	if (($subtotal)&&(count($sumar) > 0)) {
			  		if ($cant_sub_tot > 1){
			  			$cant_sub_tot= 0;
				  		$total_row= $row;
				  		foreach ($total_row as $key => $value) {
				  			$total_row[$key]= '';
				  		}
				  		foreach ($sumatoria as $key => $value) {
				  			$total_row[$key]= $value;
				  		}
				  		$key = array_keys($sumatoria);
				  		$key= $key[0];
						$position = array_search($key, array_keys($total_row), true);
						$total_keys= array_keys($total_row);
						$col_prev= $total_keys[$position-1];
						$total_row[$col_prev]= "<b>Sub Total</b>";
				  		array_push($datos, $total_row);
				  	}
				  	$sumatoria= array();
				  	$subtotal= false;
			  	}

			  	//sumar los totales
			  	if (!empty($sumar)){
				  	foreach ($sumar as $key => $value) {
				  		if(!isset($sumatoria[$value])){
				  			$sumatoria[$value]= $row[$value];
				  		}
				  		else{
				  			$sumatoria[$value]+= $row[$value];
				  		}
				  		if(!isset($sumatoria_total[$value])){
				  			$sumatoria_total[$value]= $row[$value];
				  		}
				  		else{
				  			$sumatoria_total[$value]+= $row[$value];
				  		}
				  	}
				  	$cant_sub_tot+= 1;
				}
			  }else{
			  	//solo sumar los totales
			  	if (!empty($sumar)){
				  	foreach ($sumar as $key => $value) {
				  		if(!isset($sumatoria[$value])){
				  			$sumatoria[$value]= $row[$value];
				  		}
				  		else{
				  			$sumatoria[$value]+= $row[$value];
				  		}
				  		if(!isset($sumatoria_total[$value])){
				  			$sumatoria_total[$value]= $row[$value];
				  		}
				  		else{
				  			$sumatoria_total[$value]+= $row[$value];
				  		}
				  	}
				  	$cant_sub_tot+= 1;
				}
			  }

			  array_push($datos, $row);

			  if ($i == 500) {
			  	$datos_salida= array('datos_tabla' => $datos, 'titulo_tabla' => '', 'titulos_columnas' => $titulos);
			  	$salida->tabla( $datos_salida, $muestra_nombre_columnas, 8,array("cols" => $options));
			  	$i= 0;
			  	$datos= array();
			  	$muestra_nombre_columnas= false;
			  }
			}
			$stmt = null;
		} catch (PDOException $e) {
			toba::logger()->error($e->getMessage());
		}
		if (($i > 0)&&(count($datos) > 0)) {
			if (count($sumar) > 0) {
		  		$total_row= $datos[0];
		  		foreach ($total_row as $key => $value) {
		  			$total_row[$key]= '';
		  		}
		  		foreach ($sumatoria as $key => $value) {
		  			$total_row[$key]= $value;
		  		}
		  		$key = array_keys($sumatoria);
		  		if (isset($key[0])){
			  		$key= $key[0];
					$position = array_search($key, array_keys($total_row), true);
					$total_keys= array_keys($total_row);
					$col_prev= $total_keys[$position-1];
					$total_row[$col_prev]= 'Sub Total';
					array_push($datos, $total_row);
					$total_row[$col_prev]= '';
					foreach ($sumatoria_total as $key => $value) {
						$total_row[$key]= $value;
					}
					//array_push($datos, $total_row);
				}
		  	}

			$datos_salida= array('datos_tabla' => $datos, 'titulo_tabla' => '', 'titulos_columnas' => $titulos);
		  	$salida->tabla( $datos_salida, $muestra_nombre_columnas, 8,array("cols" => $options));
		  	$i= 0;
		  	$muestra_nombre_columnas= false;
		  	$datos= array();
		  	//$pdf->ezStream(array());
		  	if (!empty($sumatoria_total)) {
			  	array_push($datos, $total_row);
			  	$datos_salida= array('datos_tabla' => $datos, 'titulo_tabla' => "<b>TOTALES</b>", 'titulos_columnas' => $titulos);
			  	$salida->tabla( $datos_salida, true, 12,array("cols" => $options));
		  	}
		  }
		  toba::logger()->info('--------------Agrupamientos--------------------');
		  toba::logger()->info($agrupamiento);
		  toba::logger()->info('--------------Agrupamientos--------------------');

		//$datos= toba::db()->consultar($query);

		/////////////////////////////////////////////////////////

		$this->armar_encabezado_consulta_pdf($pdf, $titulo, $altura_titulo, $parametros_mostrar);

		$salida->generar_salida();
		$salida->enviar_archivo();
	}

	protected function servicio__generar_consulta( $objetos )
	{
		$param_memoria= toba::memoria()->get_parametros();
		$param_param= $param_memoria['parametros'];
		$param_param= str_replace("'", '"', $param_param);
		$tipo_salida= $param_memoria['tipo_salida'];
		$altura_titulo= 20; //$param_memoria['altura_titulo'];
		$parametros_mostrar= $param_memoria['parametros_mostrar'];
		$datos= json_decode($param_param, true);
		toba::logger()->info('--------------Parametros Consulta Dinamica--------------------');
		foreach ($datos as $key => $value) {
			if (strpos($key, 'multiple') > 0) {
				toba::logger()->info($datos[$key]);
				$datos[$key]= "'".str_replace(",", "','", $datos[$key])."'";
			}
		}		
		toba::logger()->info($datos);
		toba::logger()->info('--------------Parametros Consulta Dinamica--------------------');

		$consulta= dao_consultas_dinamicas::get_consulta_x_reporte($param_memoria['reporte']);
		$parametros= $this->escanear_parametros($consulta['reporte'], $consulta['query']);
		
		$aux= array();
		for ($i=1; $i <= count($parametros); $i++) { 
			$x= $i;
			if (isset($datos["param_$x"])) {
				array_push($aux, array('parametro' => $parametros[$i], 'valor' => $datos["param_$x"]));
			}elseif (isset($datos["param_multiple_$x"])) {
				array_push($aux, array('parametro' => $parametros[$i], 'valor' => $datos["param_multiple_$x"]));
			}elseif (isset($datos["param_lista_$x"])) {
				array_push($aux, array('parametro' => $parametros[$i], 'valor' => $datos["param_lista_$x"]));
			}
		}
		
		$query= $this->reemplazar_parametros($consulta['query'], $aux);

		if ($tipo_salida == "pdf") {
			return $this->servicio__generar_consulta_pdf($objetos, $query, $consulta['nombre'], $altura_titulo, $parametros_mostrar);
		}else if($tipo_salida == "delimitado"){
			return $this->servicio__generar_consulta_delimitado($query, ";");
		}else if($tipo_salida == "excel"){
			return $this->servicio__generar_consulta_excel($objetos, $query);
		}
	}
	
	protected function servicio__vista_excel($objetos)
	{
		$salida = new toba_vista_excel();
		$salida->asignar_objetos( $objetos );
		$salida->generar_salida();
		$salida->enviar_archivo();
	}
	

	/**
	 * Genera una salida html pensada para impresión
	 */
	protected function servicio__vista_toba_impr_html( $objetos )
	{
		$pm = toba::proyecto()->get_parametro('pm_impresion');
		$clase = toba::proyecto()->get_parametro('salida_impr_html_c');
		$archivo = toba::proyecto()->get_parametro('salida_impr_html_a');
		if ( $clase && $archivo ) {
			//El proyecto posee un objeto de impresion HTML personalizado
			$punto = toba::puntos_montaje()->get_por_id($pm);
			$path  = $punto->get_path_absoluto().'/'.$archivo;
			require_once($path);
			$salida = new $clase();
		} else {
			$salida = new toba_impr_html();
		}
		$salida->asignar_objetos( $objetos );
		$salida->generar_salida();
	}

	/**
	 * Retorna el html y js localizado de un componente y sus dependencias.
	 * Pensado como respuesta a una solicitud AJAX
	 */
	protected function servicio__html_parcial($objetos)
	{
		echo "[--toba--]";		
		//-- Se reenvia el encabezado
		$this->tipo_pagina()->barra_superior();
		echo "</div>";

		//--- Parte superior de la zona
		if (toba::solicitud()->hay_zona() && toba::zona()->cargada()) {
			toba::zona()->generar_html_barra_superior();
		}
		//--- Se incluyen botones en la botonera de la operacion
		$this->generar_html_botonera_sup($objetos);		
		echo "[--toba--]";
		$ok = true;
		try {
			//--- Se envia el HTML
			foreach ($objetos as $objeto) {
				$objeto->generar_html();
			}	
		} catch(toba_error $e) {
			$ok = false;
			toba::logger()->error($e, 'toba');
			$mensaje = $e->get_mensaje();
			$mensaje_debug = null;
			if (toba::logger()->modo_debug()) {
				$mensaje_debug = $e->get_mensaje_log();
			}
			toba::notificacion()->error($mensaje, $mensaje_debug);
		}

		
		echo "[--toba--]";
		
		//-- Se envia info de debug
		if ( toba_editor::modo_prueba() ) {
			$item = toba::solicitud()->get_datos_item('item');
			$accion = toba::solicitud()->get_datos_item('item_act_accion_script');
			toba_editor::generar_zona_vinculos_item($item, $accion, false);
		}		
		echo "[--toba--]";

		//--- Se envian los consumos js		
		$consumos = array();
		foreach ($objetos as $objeto) {
			$consumos = array_merge($consumos, $objeto->get_consumo_javascript());
		}
		echo "toba.incluir(".toba_js::arreglo($consumos, false).");\n"; 
		echo "[--toba--]";
				
		//--- Se envia el javascript
		//Se actualiza el vinculo del form
		$autovinculo = toba::vinculador()->get_url();
		echo "document.formulario_toba.action='$autovinculo'\n";
		toba::vinculador()->generar_js();
		toba_js::cargar_definiciones_runtime();
		if ($ok) {
			try {
				foreach ($objetos as $objeto) {
					//$objeto->servicio__html_parcial();
					$objeto_js = $objeto->generar_js();
					echo "\nwindow.$objeto_js.iniciar();\n";
				}
			} catch (toba_error $e) {
				toba::logger()->error($e, 'toba');
				$mensaje_debug = null;
				if (toba::logger()->modo_debug()) {
					$mensaje_debug = $e->get_mensaje_log();
				}				
				toba::notificacion()->error($e->get_mensaje(), $mensaje_debug);
			}
		}
		toba::notificacion()->mostrar(false);
		toba::acciones_js()->generar_js();
		$this->generar_analizador_estadistico();
	}
	
	/**
	 * Responde los valores en cascadas de un formulario específico
	 */
	protected function servicio__cascadas_efs($objetos)
	{
		toba::memoria()->desactivar_reciclado();
		try {
			if (count($objetos) != 1) {
				$actual = count($objetos);
				throw new toba_error_def("Las cascadas sólo admiten un objeto destino (actualmente: $actual)");
			}
			$objetos[0]->servicio__cascadas_efs();
		} catch(toba_error $e) {
			toba::logger()->error($e, 'toba');
			$mensaje_debug = null;
			if (toba::logger()->modo_debug()) {
				$mensaje_debug = $e->get_mensaje_log();
			}				
			toba::notificacion()->error($e->get_mensaje(), $mensaje_debug);
		}
	}
		
	/**
	 * Servicio genérico de acceso a objetos a través de parámetros
	 */
	protected function servicio__ejecutar($objetos)
	{
		foreach ($objetos as $objeto) {
			$objeto->servicio__ejecutar();
		}
	}
	
	
	protected function servicio__ajax($objetos)
	{
		toba::memoria()->desactivar_reciclado();
		try {
			if (count($objetos) != 1) {
				$actual = count($objetos);
				throw new toba_error_def("La invocacion AJAX sólo admite un objeto destino (actualmente: $actual)");
			}
			$objetos[0]->servicio__ajax();
		} catch(toba_error $e) {
			toba::logger()->error($e, 'toba');
			$mensaje_debug = null;
			if (toba::logger()->modo_debug()) {
				$mensaje_debug = $e->get_mensaje_log();
			}				
			toba::notificacion()->error($e->get_mensaje(), $mensaje_debug);
		}
	}
	

	function registrar()
	{
		parent::registrar();
		$id_sesion = toba::manejador_sesiones()->get_id_sesion();		
		if($this->registrar_db && isset($id_sesion)){
			toba::instancia()->registrar_solicitud_browser(	$this->info['basica']['item_proyecto'], 
															$this->id, 
															toba::proyecto()->get_id(),
															toba::manejador_sesiones()->get_id_sesion(),
															$_SERVER['REMOTE_ADDR']);
		}
 	}
 	
 	
 	/**
 	 * Indica si la operacion actual permite auto 
 	 */
 	function set_autocomplete($set)
 	{
 		$this->autocomplete = $set;
 	}

	/**
	 *@private
	 */
	function hacer_seguimiento()
	{
		$cod_ga = toba::proyecto()->get_parametro('codigo_ga_tracker');
		$hacer_seguimiento =  (isset($cod_ga) && trim($cod_ga) != '') ;
		return $hacer_seguimiento;
	}

	/**
	 *@private
	 * @return <type>
	 */
	function es_item_login()
	{
		$es_login = (toba::proyecto()->get_parametro('item_pre_sesion') == $this->item[1]);
		return $es_login;
	}
	
	/**
	 * @private
	 */
	function generar_analizador_estadistico()
	{
		$cod_ga = toba::proyecto()->get_parametro('codigo_ga_tracker');
		if (isset($cod_ga) && trim($cod_ga) != '') {		//No llamo a la funcion xq ya tengo el valor aca
			if (! $this->es_item_login()) {
				echo "estadista.set_codigo('$cod_ga'); \n";		
				echo "estadista.iniciar(); \n";
				echo "estadista.add_operacion('{$this->item[1]}'); \n";
				echo "estadista.add_titulo('". $this->get_datos_item('item_nombre')."'); \n";
				$ventana = toba::proyecto()->get_parametro('sesion_tiempo_no_interac_min');
				if ($ventana != 0) {
					$ventana *= 60; //$ventana esta en minutos y necesito segundos
					echo "estadistica.set_timeout('$ventana'); \n";
				}
				echo "estadista.trace()";
			} else { //Es item de login
				//Tengo que cerrar el tag js que viene abierto de antes
				echo "</script><script type=\"text/javascript\">"			
				."var gaJsHost = ((\"https:\" == document.location.protocol) ? \"https://ssl.\" : \"http://www.\");"
				."document.write(unescape(\"%3Cscript src='\" + gaJsHost + \"google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E\"));"
				."</script>"
				."<script type=\"text/javascript\">"
				."try {"
				."var pageTracker = _gat._getTracker(\"$cod_ga\");"
				."pageTracker._trackPageview();"
				."} catch(err) {}</script><script type=\"text/javascript\">";
			}
		}
	}

	//----------------------------------------------------------
	//---------------------- TIPO de PAGINA --------------------
	//----------------------------------------------------------

	/**
	 * @return toba_tp_normal
	 */
	function tipo_pagina()
	{
		if (! isset($this->tipo_pagina)) {
			//Carga el TP a demanda
			if (!class_exists($this->info['basica']['tipo_pagina_clase'])){
				if ($this->info['basica']['tipo_pagina_archivo']) {
					$punto = toba::puntos_montaje()->get_por_id($this->info['basica']['punto_montaje']);
					$path  = $punto->get_path_absoluto().'/'.$this->info['basica']['tipo_pagina_archivo'];
					require_once($path);
				}
			}
			$this->tipo_pagina = new $this->info['basica']['tipo_pagina_clase']();
		}
		return $this->tipo_pagina;
	}
	
	//----------------------------------------------------------------
	//-- Consumo de solicitudes desde los casos de testeo
	//----------------------------------------------------------------
	
	function inicializacion_pasiva()
	{
		$this->crear_zona();			
		$this->cargar_objetos();
	}

	function cn()
	{
		return $this->objetos[$this->cn];	
	}
	
	function ci()
	{
		return $this->objetos[$this->cis[0]];
	}

}
?>
