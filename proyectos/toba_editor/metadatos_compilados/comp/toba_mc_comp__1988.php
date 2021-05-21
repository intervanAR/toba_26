<?php

class toba_mc_comp__1988
{
	static function get_metadatos()
	{
		return array (
  '_info' => 
  array (
    'proyecto' => 'toba_editor',
    'objeto' => 1988,
    'anterior' => NULL,
    'identificador' => NULL,
    'reflexivo' => NULL,
    'clase_proyecto' => 'toba',
    'clase' => 'toba_ci',
    'subclase' => 'ci_abms_principal',
    'subclase_archivo' => 'asistentes/abm_simple/ci_abms_principal.php',
    'objeto_categoria_proyecto' => NULL,
    'objeto_categoria' => NULL,
    'nombre' => 'Asistente - ABMS',
    'titulo' => NULL,
    'colapsable' => 0,
    'descripcion' => NULL,
    'fuente_proyecto' => NULL,
    'fuente' => NULL,
    'solicitud_registrar' => NULL,
    'solicitud_obj_obs_tipo' => NULL,
    'solicitud_obj_observacion' => NULL,
    'parametro_a' => NULL,
    'parametro_b' => NULL,
    'parametro_c' => NULL,
    'parametro_d' => NULL,
    'parametro_e' => NULL,
    'parametro_f' => NULL,
    'usuario' => NULL,
    'creacion' => '2007-07-16 19:32:59',
    'punto_montaje' => 12,
    'clase_editor_proyecto' => 'toba_editor',
    'clase_editor_item' => '1000249',
    'clase_archivo' => 'nucleo/componentes/interface/toba_ci.php',
    'clase_vinculos' => NULL,
    'clase_editor' => '1000249',
    'clase_icono' => 'objetos/multi_etapa.gif',
    'clase_descripcion_corta' => 'ci',
    'clase_instanciador_proyecto' => 'toba_editor',
    'clase_instanciador_item' => '1642',
    'objeto_existe_ayuda' => NULL,
    'ap_clase' => NULL,
    'ap_archivo' => NULL,
    'ap_punto_montaje' => NULL,
    'cant_dependencias' => 11,
    'posicion_botonera' => 'abajo',
  ),
  '_info_eventos' => 
  array (
  ),
  '_info_puntos_control' => 
  array (
  ),
  '_info_ci' => 
  array (
    'ev_procesar_etiq' => NULL,
    'ev_cancelar_etiq' => NULL,
    'objetos' => NULL,
    'ancho' => '100%',
    'alto' => NULL,
    'posicion_botonera' => 'abajo',
    'tipo_navegacion' => 'tab_h',
    'con_toc' => 0,
    'botonera_barra_item' => NULL,
  ),
  '_info_ci_me_pantalla' => 
  array (
    0 => 
    array (
      'pantalla' => 1059,
      'identificador' => 'pant_basica',
      'etiqueta' => 'Informacion b�sica',
      'descripcion' => NULL,
      'tip' => NULL,
      'imagen_recurso_origen' => 'apex',
      'imagen' => NULL,
      'objetos' => NULL,
      'eventos' => NULL,
      'orden' => 1,
      'punto_montaje' => 12,
      'subclase' => 'pant_basico',
      'subclase_archivo' => 'asistentes/abm_simple/pant_basico.php',
      'template' => NULL,
      'template_impresion' => NULL,
    ),
    1 => 
    array (
      'pantalla' => 1000171,
      'identificador' => 'pant_cuadro',
      'etiqueta' => 'Cuadro',
      'descripcion' => NULL,
      'tip' => NULL,
      'imagen_recurso_origen' => 'apex',
      'imagen' => NULL,
      'objetos' => NULL,
      'eventos' => NULL,
      'orden' => 2,
      'punto_montaje' => 12,
      'subclase' => NULL,
      'subclase_archivo' => NULL,
      'template' => NULL,
      'template_impresion' => NULL,
    ),
    2 => 
    array (
      'pantalla' => 1000172,
      'identificador' => 'pant_form',
      'etiqueta' => 'Formulario',
      'descripcion' => NULL,
      'tip' => NULL,
      'imagen_recurso_origen' => 'apex',
      'imagen' => NULL,
      'objetos' => NULL,
      'eventos' => NULL,
      'orden' => 3,
      'punto_montaje' => 12,
      'subclase' => NULL,
      'subclase_archivo' => NULL,
      'template' => NULL,
      'template_impresion' => NULL,
    ),
    3 => 
    array (
      'pantalla' => 1000173,
      'identificador' => 'pant_filtro',
      'etiqueta' => 'Filtro',
      'descripcion' => NULL,
      'tip' => NULL,
      'imagen_recurso_origen' => 'apex',
      'imagen' => NULL,
      'objetos' => NULL,
      'eventos' => NULL,
      'orden' => 4,
      'punto_montaje' => 12,
      'subclase' => NULL,
      'subclase_archivo' => NULL,
      'template' => NULL,
      'template_impresion' => NULL,
    ),
  ),
  '_info_obj_pantalla' => 
  array (
    0 => 
    array (
      'pantalla' => 1059,
      'proyecto' => 'toba_editor',
      'objeto_ci' => 1988,
      'dep_id' => 934,
      'orden' => 1,
      'identificador_pantalla' => 'pant_basica',
      'identificador_dep' => 'form_molde',
    ),
    1 => 
    array (
      'pantalla' => 1000171,
      'proyecto' => 'toba_editor',
      'objeto_ci' => 1988,
      'dep_id' => 1000173,
      'orden' => 1,
      'identificador_pantalla' => 'pant_cuadro',
      'identificador_dep' => 'form_cuadro',
    ),
    2 => 
    array (
      'pantalla' => 1000172,
      'proyecto' => 'toba_editor',
      'objeto_ci' => 1988,
      'dep_id' => 1000174,
      'orden' => 1,
      'identificador_pantalla' => 'pant_form',
      'identificador_dep' => 'form_presentacion',
    ),
    3 => 
    array (
      'pantalla' => 1000173,
      'proyecto' => 'toba_editor',
      'objeto_ci' => 1988,
      'dep_id' => 1000178,
      'orden' => 1,
      'identificador_pantalla' => 'pant_filtro',
      'identificador_dep' => 'form_filtro',
    ),
    4 => 
    array (
      'pantalla' => 1000171,
      'proyecto' => 'toba_editor',
      'objeto_ci' => 1988,
      'dep_id' => 1000175,
      'orden' => 2,
      'identificador_pantalla' => 'pant_cuadro',
      'identificador_dep' => 'form_cuadro_carga',
    ),
    5 => 
    array (
      'pantalla' => 1000173,
      'proyecto' => 'toba_editor',
      'objeto_ci' => 1988,
      'dep_id' => 1000179,
      'orden' => 2,
      'identificador_pantalla' => 'pant_filtro',
      'identificador_dep' => 'ml_filtro_filas',
    ),
    6 => 
    array (
      'pantalla' => 1000172,
      'proyecto' => 'toba_editor',
      'objeto_ci' => 1988,
      'dep_id' => 1000176,
      'orden' => 2,
      'identificador_pantalla' => 'pant_form',
      'identificador_dep' => 'cuadro_form_filas',
    ),
    7 => 
    array (
      'pantalla' => 1059,
      'proyecto' => 'toba_editor',
      'objeto_ci' => 1988,
      'dep_id' => 1000160,
      'orden' => 2,
      'identificador_pantalla' => 'pant_basica',
      'identificador_dep' => 'form_basico',
    ),
    8 => 
    array (
      'pantalla' => 1059,
      'proyecto' => 'toba_editor',
      'objeto_ci' => 1988,
      'dep_id' => 1000164,
      'orden' => 3,
      'identificador_pantalla' => 'pant_basica',
      'identificador_dep' => 'form_filas',
    ),
    9 => 
    array (
      'pantalla' => 1000172,
      'proyecto' => 'toba_editor',
      'objeto_ci' => 1988,
      'dep_id' => 1000177,
      'orden' => 3,
      'identificador_pantalla' => 'pant_form',
      'identificador_dep' => 'form_form_fila',
    ),
  ),
  '_info_evt_pantalla' => 
  array (
  ),
  '_info_dependencias' => 
  array (
    0 => 
    array (
      'identificador' => 'form_presentacion',
      'proyecto' => 'toba_editor',
      'objeto' => 1000316,
      'clase' => 'toba_ei_formulario',
      'clase_archivo' => 'nucleo/componentes/interface/toba_ei_formulario.php',
      'subclase' => NULL,
      'subclase_archivo' => NULL,
      'fuente' => 'instancia',
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
    1 => 
    array (
      'identificador' => 'form_cuadro',
      'proyecto' => 'toba_editor',
      'objeto' => 1000315,
      'clase' => 'toba_ei_formulario',
      'clase_archivo' => 'nucleo/componentes/interface/toba_ei_formulario.php',
      'subclase' => NULL,
      'subclase_archivo' => NULL,
      'fuente' => 'instancia',
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
    2 => 
    array (
      'identificador' => 'form_filtro',
      'proyecto' => 'toba_editor',
      'objeto' => 1000319,
      'clase' => 'toba_ei_formulario',
      'clase_archivo' => 'nucleo/componentes/interface/toba_ei_formulario.php',
      'subclase' => NULL,
      'subclase_archivo' => NULL,
      'fuente' => 'instancia',
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
    3 => 
    array (
      'identificador' => 'form_molde',
      'proyecto' => 'toba_editor',
      'objeto' => 1000299,
      'clase' => 'toba_ei_formulario',
      'clase_archivo' => 'nucleo/componentes/interface/toba_ei_formulario.php',
      'subclase' => 'form_molde',
      'subclase_archivo' => 'asistentes/form_molde.php',
      'fuente' => 'instancia',
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
    4 => 
    array (
      'identificador' => 'form_basico',
      'proyecto' => 'toba_editor',
      'objeto' => 1000293,
      'clase' => 'toba_ei_formulario',
      'clase_archivo' => 'nucleo/componentes/interface/toba_ei_formulario.php',
      'subclase' => 'form_basico',
      'subclase_archivo' => 'asistentes/abm_simple/form_basico.php',
      'fuente' => 'instancia',
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
    5 => 
    array (
      'identificador' => 'ml_filtro_filas',
      'proyecto' => 'toba_editor',
      'objeto' => 1000320,
      'clase' => 'toba_ei_formulario_ml',
      'clase_archivo' => 'nucleo/componentes/interface/toba_ei_formulario_ml.php',
      'subclase' => NULL,
      'subclase_archivo' => NULL,
      'fuente' => 'instancia',
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
    6 => 
    array (
      'identificador' => 'form_filas',
      'proyecto' => 'toba_editor',
      'objeto' => 1000297,
      'clase' => 'toba_ei_formulario_ml',
      'clase_archivo' => 'nucleo/componentes/interface/toba_ei_formulario_ml.php',
      'subclase' => NULL,
      'subclase_archivo' => NULL,
      'fuente' => 'instancia',
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
    7 => 
    array (
      'identificador' => 'datos',
      'proyecto' => 'toba_editor',
      'objeto' => 1000294,
      'clase' => 'toba_datos_relacion',
      'clase_archivo' => 'nucleo/componentes/persistencia/toba_datos_relacion.php',
      'subclase' => NULL,
      'subclase_archivo' => NULL,
      'fuente' => 'instancia',
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
    8 => 
    array (
      'identificador' => 'form_cuadro_carga',
      'proyecto' => 'toba_editor',
      'objeto' => 1000317,
      'clase' => 'toba_ei_formulario',
      'clase_archivo' => 'nucleo/componentes/interface/toba_ei_formulario.php',
      'subclase' => 'form_carga_sql_opciones',
      'subclase_archivo' => 'asistentes/form_carga_sql_opciones.php',
      'fuente' => 'instancia',
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
    9 => 
    array (
      'identificador' => 'form_form_fila',
      'proyecto' => 'toba_editor',
      'objeto' => 1000317,
      'clase' => 'toba_ei_formulario',
      'clase_archivo' => 'nucleo/componentes/interface/toba_ei_formulario.php',
      'subclase' => 'form_carga_sql_opciones',
      'subclase_archivo' => 'asistentes/form_carga_sql_opciones.php',
      'fuente' => 'instancia',
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
    10 => 
    array (
      'identificador' => 'cuadro_form_filas',
      'proyecto' => 'toba_editor',
      'objeto' => 1000318,
      'clase' => 'toba_ei_cuadro',
      'clase_archivo' => 'nucleo/componentes/interface/toba_ei_cuadro.php',
      'subclase' => NULL,
      'subclase_archivo' => NULL,
      'fuente' => NULL,
      'parametros_a' => NULL,
      'parametros_b' => NULL,
    ),
  ),
);
	}

}

?>