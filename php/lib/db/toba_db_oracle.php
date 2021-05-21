<?php
/**
 * Driver de conexión con oracle
 * @package Fuentes
 * @subpackage Drivers
 */
class toba_db_oracle extends toba_db
{
	protected $cache_metadatos = array(); //Guarda un cache de los metadatos de cada tabla
	protected $schema;
	protected $transaccion_abierta = false;
	protected $charset;


	function __construct($profile, $usuario, $clave, $base, $puerto)
	{
		$this->motor = "oracle";
		$this->charset = "WE8ISO8859P1";
		parent::__construct($profile, $usuario, $clave, $base, $puerto);
		//$this->setear_datestyle_iso();
	}
	
	/**
	*	Crea una conexion a la base
	*	@throws toba_error_db en caso de error
	*/
	function conectar()
	{
	    parent::conectar();
		if(isset($this->conexion)) {
			$this->conexion->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);
	    }
	    $this->set_datestyle_iso();
            $this->set_numericstyle_iso();
	}
	
	/**
	*	Cierra la conexion actual y crea una nueva conexion a la base
	*	@throws toba_error_db en caso de error
	*/
	function reconectar()
	{
		$this->destruir();
		$this->conectar();
		$this->set_schema($this->get_schema());
	}
	
	function get_dsn()
	{
		//return "oci:dbname=//{$this->profile}:{$this->puerto}/{$this->base}";	
        return 'oci:dbname=//'.$this->profile.':'.$this->puerto.'/'.$this->base.';charset='.$this->charset;
                
	}

	function get_parametros()
	{
		$parametros = parent::get_parametros();
		$parametros['schema'] = $this->schema;
		return $parametros;
	}
	/**
	 * Determina que schema se utilizar? por defecto para la ejecuci?n de consultas, comandos y consulta de metadatos
	 * @param string $schema
	 * @param boolean $ejecutar
	 */
	function set_schema($schema, $ejecutar = true, $fallback_en_public=false)
	{
		$this->schema = $schema;
		$sql = "ALTER SESSION SET CURRENT_SCHEMA= $schema";
                if ($fallback_en_public) {
			$sql .= ', public';
		}
                if (! $ejecutar) { return $sql; }
		$this->ejecutar($sql);
	}

	function set_usuario($usuario)
	{
		$sql = "BEGIN PKG_CONTEXTOS.setea_usuario(:usuario); END;";
               	
                $parametros = array(array(  'nombre' => 'usuario', 
                                            'tipo_dato' => PDO::PARAM_STR,
                                            'longitud' => 32,
                                            'valor' => $usuario)
                             );
                $this->ejecutar_store_procedure($sql, $parametros);
                
	}

	function set_formulario($formulario)
	{
		// Consultar si esta creado el context CONTEXTOS en la base

		$sql = "select count(1) as cant from all_objects 
		        where object_name = 'CONTEXTOS';";
		
		$datos=$this->consultar($sql);
		$cant =$datos[0]['cant'];

		// Si existe el contexto etnonces setear nombre de formulario actual FORM_AUDITA
		if ($cant == 1) {

		  $sql = "BEGIN PKG_CONTEXTOS.setear_contexto(:contexto, :atributo, :valor); END;";
		  $parametros = array(array( 'nombre' => 'contexto', 
                                   'tipo_dato' => PDO::PARAM_STR,
                                   'longitud' => 32,
                                   'valor' => 'CONTEXTOS'),
		                    array( 'nombre' => 'atributo', 
                                   'tipo_dato' => PDO::PARAM_STR,
                                   'longitud' => 32,
                                   'valor' => 'FORM_AUDITA'),
                            array( 'nombre' => 'valor', 
                                   'tipo_dato' => PDO::PARAM_STR,
                                   'longitud' => 100,
                                   'valor' => $formulario),
		                   );
		
		  $this->ejecutar_store_procedure($sql, $parametros);
		}
	}

	function get_sesionid()
	{
		$sql = "select userenv('sessionid') as sid from dual";
		$datos = $this->consultar_fila($sql);
		$sid = $datos['sid'];

		return $sid;
	}
                
	function get_schema()
	{
		if (isset($this->schema)) {
			return $this->schema;
		}
	}

	function set_encoding($encoding)
	{
		$this->charset = $encoding;
		$this->reconectar();
	}
	
	function set_datestyle_iso()
	{
	    $sql = "ALTER SESSION SET NLS_DATE_FORMAT='YYYY-MM-DD HH24:MI:SS'";
		$this->ejecutar($sql);
	}
        
        function set_numericstyle_iso()
        {
            $sql = "ALTER SESSION SET NLS_NUMERIC_CHARACTERS='.,'";
            $this->ejecutar($sql);
        }
	
		 
	/**
	 *  Crea el lenguaje plpgsql unicamente si el mismo aun no existe para la base de datos.
	 */
	/* No implementado en Oracle
	function crear_lenguaje_procedural()
	{
	}
	*/
	
	/**
	*	Recupera el valor actual de una secuencia
	*	@param string $secuencia Nombre de la secuencia
	*	@return string Siguiente numero de la secuencia
	*/	
	function recuperar_secuencia($secuencia, $ejecutar=true)
	{
		$sql = "SELECT ($secuencia) as seq FROM DUAL;";
		if (! $ejecutar) { return $sql; }
		toba::logger()->debug('Recuperar secuencia');
		$datos = $this->consultar($sql);
		toba::logger()->debug('Secuencia: '.$datos[0]['seq']);
		return $datos[0]['seq'];
	}

	function recuperar_nuevo_valor_secuencia($secuencia, $ejecutar = true)
	{
		$sql = "SELECT ($secuencia) + 1 as seq FROM DUAL;";
		if (! $ejecutar) { return $sql; }
		$datos = $this->consultar($sql);
		return $datos[0]['seq'];
	}
		
	function retrazar_constraints($retrazar = true)
	{
		$tipo = $retrazar ? 'DEFERRED' : 'IMMEDIATE';
		$this->ejecutar("SET CONSTRAINTS ALL $tipo;");
		toba_logger::instancia()->debug("************ Se cambia el chequeo de constraints ($tipo) ****************", 'toba');		
	}

	function abrir_transaccion()
	{
	    parent::abrir_transaccion();
	    $this->transaccion_abierta = true;
	}
	
	function abortar_transaccion()
	{
	    parent::abortar_transaccion();
	    $this->transaccion_abierta = false;
	}
	
	function cerrar_transaccion()
	{
	    parent::cerrar_transaccion();
	    $this->transaccion_abierta = false;
	}

	/**
	 * @return boolean Devuelve true si hay una transacci?n abierta y false en caso contrario
	 */
	function transaccion_abierta()
	{
		return $this->transaccion_abierta;
	}

	/* No implementado en Oracle
	function agregar_savepoint($nombre)
	{
	}
	*/
	
	/* No implementado en Oracle
	function abortar_savepoint($nombre)
	{
	}
	*/
	
	/* No implementado en Oracle
	function liberar_savepoint($nombre)
	{
	}
	*/
	
	/**
	*	Insert de datos desde un arreglo hacia una tabla. Requiere la extension original pgsql.
	*	@param string $tabla Nombre de la tabla en la que se insertar?n los datos
	*	@param array $datos Los datos a insertar: cada elemento del arreglo ser? un registro en la tabla.
	*	@param string $delimitador Separador de datos de cada fila.
	*	@param string $valor_nulo Cadena que se utlilizar? como valor nulo.
	*	@return boolean Retorn TRUE en caso de ?xito o FALSE en caso de error.
	*/
/*	function insert_masivo($tabla,$datos,$delimitador="\t",$valor_nulo="\\N") {
		$puerto = ($this->puerto != '') ? "port={$this->puerto}": '';
		$host = "host={$this->profile}";
		$base = "dbname={$this->base}";
		$usuario = "user={$this->usuario}";
		$clave = "password={$this->clave}";
		$conn_string = "$host $puerto $base $usuario $clave";
		$dbconn = pg_connect($conn_string);
		if (isset($this->schema)) {
			$sql = "SET search_path TO {$this->schema};";
			pg_query($dbconn, $sql);
		}
		$salida = pg_copy_from($dbconn,$tabla,$datos,$delimitador,$valor_nulo);
		if (!$salida) {
			$mensaje = pg_last_error($dbconn);
			pg_close($dbconn);
			toba::logger()->error($mensaje);
			throw new toba_error($mensaje);
		}
		pg_close($dbconn);
		return $salida;
	}
*/
	//------------------------------------------------------------------------
	//-- SCHEMAS Oracle
	//------------------------------------------------------------------------	
	
	function existe_schema($esquema) 
	{
		$esquema = $this->quote($esquema);
		$sql = "SELECT COUNT(*) as cant
			FROM dba_objects;
			WHERE owner = $esquema;";
		$rs = $this->consultar_fila($sql);
		return $rs['cant'] > 0;
	}
	
	function borrar_schema($schema, $ejecutar = true)
	{
		$sql = "DROP USER $schema CASCADE;";
		if (! $ejecutar) { return $sql; }
		return $this->ejecutar($sql);
	}		

	function borrar_schema_si_existe($schema)
	{
		if ($this->existe_schema($schema)) {
			$this->borrar_schema($schema);
		}
	}
	
	function crear_schema($schema, $ejecutar = true) 
	{
		$sql = "CREATE USER $schema IDENTIFIED BY $schema;";
		if (! $ejecutar) { return $sql; }
		return $this->ejecutar($sql);
	}
	
	/* No implementado en Oracle
	function renombrar_schema($actual, $nuevo, $ejecutar = true) 
	{
	}		
	*/
	
	function get_lista_schemas_disponibles()
	{
		$sql = 'SELECT DISTINCT owner as esquema
			FROM dba_objects;';
		return $this->consultar($sql);
	}
	
	/* No implementado en Oracle
	function existe_lenguaje($lang)
	{
	}
	*/
	
	/* No implementado en Oracle
	function crear_lenguaje($lang)
	{
	}
	*/

    /**
     * Clona el schema actual en $nuevo_schema. FUNCIONA EN POSTGRES >= 8.3
     * @param string $actual el nombre del schema a clonar
     * @param string $nuevo el nombre del nuevo schema
     */
/*    public function clonar_schema($actual, $nuevo)
    {
		if (!$this->existe_lenguaje('plpgsql')) {
			$this->crear_lenguaje('plpgsql');
		}
		
		$sql = "
            CREATE OR REPLACE FUNCTION clone_schema(source_schema text, dest_schema text) RETURNS void AS
            \$BODY$
            DECLARE
              objeto text;
              buffer text;
            BEGIN
                EXECUTE 'CREATE SCHEMA ' || dest_schema ;

                FOR objeto IN
                    SELECT sequence_name FROM information_schema.sequences WHERE sequence_schema = source_schema
                LOOP
                    buffer := dest_schema || '.' || objeto;
                    EXECUTE 'CREATE SEQUENCE ' || buffer;
                    BEGIN
                        --EXECUTE 'SELECT setval(' || quote_literal(buffer) ||', (SELECT nextval(' || quote_literal(source_schema ||'.'|| objeto) || '))' || ')';
                        EXECUTE 'SELECT setval(' || quote_literal(buffer) ||', (SELECT last_value FROM ' || source_schema ||'.'|| objeto || ')' || ')';
                    EXCEPTION WHEN OTHERS THEN
                    END;
                END LOOP;

                FOR objeto IN
                    SELECT table_name::text FROM information_schema.tables WHERE table_schema = source_schema
                LOOP
                    buffer := dest_schema || '.' || objeto;
                    EXECUTE 'CREATE TABLE ' || buffer || ' (LIKE ' || source_schema || '.' || objeto || ' INCLUDING CONSTRAINTS INCLUDING INDEXES INCLUDING DEFAULTS)';
                    EXECUTE 'INSERT INTO ' || buffer || '(SELECT * FROM ' || source_schema || '.' || objeto || ')';
                END LOOP;

            END;
            \$BODY$
            LANGUAGE plpgsql;
        ";
		
        $this->ejecutar($sql);
        
        $actual = $this->quote($actual);
        $nuevo = $this->quote($nuevo);
        $sql = "SELECT clone_schema($actual, $nuevo);";
        $this->ejecutar($sql);
    }
*/
	//---------------------------------------------------------------------
	//-- PERMISOS
	//---------------------------------------------------------------------
	
	function get_usuario_actual()
	{
		$sql = 'SELECT USER AS usuario FROM DUAL;';
		$datos = toba::db()->consultar_fila($sql);
		return $datos['usuario'];
	}
	
	function get_rol_actual()
	{
		$sql = 'SELECT ROLE AS role FROM SESSION_ROLES;';
		$datos = $this->consultar_fila($sql);
		return $datos['role'];
	}	
	
	function set_rol($rol, $ejecutar = true)
	{
		$sql = "SET ROLE $rol;";
		if (! $ejecutar) { return $sql; }
		return $this->ejecutar($sql);
	}
	
	function existe_rol($rol) 
	{
		$datos = $this->listar_roles($rol);
		return !empty($datos);
	}
	
	function listar_roles($rol = null)
	{
		$sql = 'SELECT ROLE AS role FROM SESSION_ROLES;';
		if (! is_null($rol)) {
			$rol = $this->quote($rol);
			$sql .= " WHERE ROLE = $rol;";
		}
		return $this->consultar($sql);
	}	
	
	function crear_rol($rol, $ejecutar=true)
	{
		$sql = "CREATE ROLE $rol;";
		if (! $ejecutar) { return $sql; }		
		return $this->ejecutar($sql);		
	}
	
	function crear_usuario($rol, $password, $ejecutar = true)
	{
		$password = $this->quote($password);
		$sql = "CREATE ROLE $rol IDENTIFIED BY $password;";
		if (! $ejecutar) { return $sql; }
		return $this->ejecutar($sql);
	}	
	
	function borrar_rol($rol, $ejecutar = true)
	{
		$sql = "DROP ROLE $rol;";
		if (! $ejecutar) { return $sql; }
		return $this->ejecutar($sql);
	}
	
	function grant_rol($usuario, $rol, $ejecutar = true)
	{
		$sql = "GRANT $rol TO $usuario;";
		if (! $ejecutar) { return $sql; }
		return $this->ejecutar($sql);
	}	

	function grant_schema($usuario, $schema, $permisos = 'USAGE', $ejecutar = true)
	{
		$sql = "select 'grant $permisos on '||object_name||' to $usuario;' from user_objects
			where object_type not in ('INDEX','LOB','SYNONYM','TRIGGER','TABLE PARTITION');";
		if (! $ejecutar) { return $sql; }
		return $this->ejecutar($sql);
	}	
	/*
	function revoke_schema($usuario, $schema, $permisos = 'ALL PRIVILEGES', $ejecutar = true)
	{
		$sql = array();
		//--Revoka las tablas dentro
		$sql_rs = "SELECT
					relname
				FROM pg_class c
					JOIN pg_namespace ns ON (c.relnamespace = ns.oid)
				WHERE
						relkind in ('r','v','S')
					AND nspname = '$schema' ;";
		$rs = $this->consultar($sql_rs);
		foreach ($rs as $tabla) {
			$sql[] = "REVOKE $permisos ON $schema.{$tabla['relname']} FROM $usuario CASCADE;";
		}
		
		$sql[] = "REVOKE $permisos ON SCHEMA \"$schema\" FROM $usuario;";
		if (! $ejecutar) { return $sql; }
		$this->ejecutar($sql);		
	}
	*/
	function revoke_tablas($usuario, $schema, $tablas, $permisos = 'ALL PRIVILEGES', $ejecutar=true)
	{
		$sql = array();
		foreach ($tablas as $tabla) {
			$sql[] = "REVOKE $permisos ON $schema.{$tabla} FROM $usuario;";
		}
		if (! $ejecutar) {
			return $sql;
		}
		$this->ejecutar($sql);	
	}
	
	function revoke_rol($usuario, $rol, $ejecutar = true)
	{
		$sql = "REVOKE $rol FROM $usuario;";
		if (! $ejecutar) { return $sql; }
		return $this->ejecutar($sql);
	}

	/**
	 *	Da permisos especificos a todas las tablas de un esquema dado
	 **/
/*	function grant_tablas_schema($usuario, $schema, $privilegios ='ALL PRIVILEGES')
	{
		$sql = "SELECT
					relname
				FROM pg_class c
					JOIN pg_namespace ns ON (c.relnamespace = ns.oid)
				WHERE
						relkind in ('r','v','S')
					AND nspname = ".$this->quote($schema);
		$tablas = $this->consultar($sql);
		$this->grant_tablas($usuario, $schema, aplanar_matriz($tablas, 'relname'), $privilegios);
	}	
	
	/**
	 *	Da permisos especificos a todas las tablas de un esquema dado
	 **/
/*	function grant_tablas($usuario, $schema, $tablas, $privilegios ='ALL PRIVILEGES', $ejecutar = true)
	{
		$sql = array();
		foreach ($tablas as $tabla) {
			$sql[] = "GRANT $privilegios ON $schema.$tabla TO $usuario;";
		}
		if (! $ejecutar) { return $sql; }
		$this->ejecutar($sql);
	}	

	function grant_sp_schema($usuario, $schema,  $privilegios = 'ALL PRIVILEGES', $ejecutar = true)
	{
		$stored_procedures = $this->get_sp_schema($schema);				//Busco todos los stored procedures del schema
		$sql = "GRANT $privilegios ON FUNCTION ";
		foreach ($stored_procedures as $sp) {												//Los agrego separados por coma para usar 1 sola SQL
			$sql .= " $schema.$sp(), ";
		}
		$sql = substr($sql, 0, -2) . " TO $usuario;";											//Agrego el rol/usuario beneficiario
		if (! $ejecutar) { return $sql; }
		$this->ejecutar($sql);
	}

	function revoke_sp_schema($usuario, $schema,  $privilegios = 'ALL PRIVILEGES', $ejecutar = true)
	{
		$stored_procedures = $this->get_sp_schema($schema);				//Busco todos los stored procedures del schema
		$sql = "REVOKE $privilegios ON FUNCTION ";
		foreach ($stored_procedures as $sp) {
			$sql .= "$schema.$sp(), ";																	//Los agrego separados por coma para usar 1 sola SQL
		}
		$sql = substr($sql, 0 , -2) .  " FROM $usuario;";
		if (! $ejecutar) { return $sql; }
		$this->ejecutar($sql);
	}

	function get_sp_schema($schema)
	{
		$sql = "SELECT
											proname
						 FROM pg_proc p
							JOIN pg_namespace ns ON (p.pronamespace = ns.oid)
						WHERE
							nspname =  ".$this->quote($schema);
		$stored_proc = $this->consultar($sql);
		return aplanar_matriz($stored_proc, 'proname');								//Devuelvo la matriz sin el subindice de nombre de columna
	}
*/	
	//------------------------------------------------------------------------
	//-- INSPECCION del MODELO de DATOS
	//------------------------------------------------------------------------	
		
	function get_lista_tablas_y_vistas()
	{
		$esquema = null;
		if (isset($this->schema)) {
			$esquema = $this->schema;
		}		
		return $this->get_lista_tablas_bd(true, $esquema);	
	}
		
	function get_lista_tablas($incluir_vistas=false, $esquema=null)
	{
		if (is_null($esquema) && isset($this->schema)) {
			$esquema = $this->schema;
		}
		return $this->get_lista_tablas_bd($incluir_vistas, $esquema);
	}
		
	function get_lista_tablas_bd($incluir_vistas=false, $esquema=null)
	{
		$sql_esquema = '';
		if (! is_null($esquema)) {
			$esquema = $this->quote($esquema);
			if (is_array($esquema)) {
				$sql_esquema .= " AND owner IN (" .implode(',' , $esquema) .")" ;
			} else {
				$sql_esquema .= " AND owner= $esquema " ;
			}
		}
		$sql = "SELECT table_name as nombre,
					  owner as esquema
				FROM 
					dba_tables
				WHERE 
					table_name NOT LIKE 'pg_%'
					AND table_name NOT LIKE 'sql_%' 
					AND owner NOT LIKE 'sys%'
					AND owner != 'information_schema'
					$sql_esquema
		";
                if ($incluir_vistas) {
			$sql .= "
				UNION
				SELECT view_name as nombre,
						owner as esquema
				FROM 
					dba_views
				WHERE 
					view_name NOT LIKE 'pg_%'
					AND view_name NOT LIKE 'sql_%' 
					AND owner NOT LIKE 'pg_temp_%'
					AND owner != 'information_schema'
					$sql_esquema
			";			
		}
		$sql .= ' ORDER BY nombre';
		return $this->consultar($sql);
	}
                
		
	function existe_tabla($schema, $tabla)
	{
		$tabla = $this->quote($tabla);
		$schema = $this->quote($schema);
		
		$sql = "SELECT table_name 
			FROM all_tables
			WHERE owner = $schema
			AND table_name = $tabla;";

		$rs = $this->consultar_fila($sql);
		return !empty($rs);
	}	
	
	
	function existe_columna($columna, $tabla)
	{
		$tabla = $this->quote($tabla);
		$sql = "SELECT atc.COLUMN_NAME as nombre
			FROM all_tab_cols atc
			WHERE atc.table_name=$tabla;";
		foreach ($this->consultar($sql) as $campo) {
			if ($campo['nombre'] == $columna) {
				return true;
			}
		}
		return false;
	}
	
	/* No implementado en Oracle
	function get_lista_secuencias($esquema=null)
	{
	}
	*/
	
	/**
	*	Busca la definicion de un TABLA. Cachea los resultados por un pedido de pagina
	*/
	function get_definicion_columnas($tabla, $esquema=null)
	{
		$where = '';
		if (isset($esquema)) {
			$esquema = $this->quote($esquema);
			$where .= " AND atc.owner = $esquema" ;
		}
		if (isset($this->cache_metadatos[$tabla])) {
			return $this->cache_metadatos[$tabla];
		}
		$tabla_sana = $this->quote($tabla);
		//1) Busco definicion
		$sql = "SELECT  LOWER(atc.column_name) AS nombre, 
				atc.data_type AS tipo,
				CASE 
				    WHEN atc.data_precision IS NULL AND atc.data_scale IS NULL THEN -1
				    ELSE atc.data_length
				END AS tipo_longitud,
				CASE 
				    WHEN atc.data_precision IS NULL AND atc.data_scale IS NULL THEN atc.data_length
				    ELSE -1
				END AS longitud, 
				'' AS tipo_sql,
				DECODE(atc.nullable, 'Y', 'f', 't') AS not_null,
				CASE
				   WHEN NVL (atc.default_length, 0) > 0 THEN 1
				   ELSE 0
				END AS tiene_predeterminado,
				atc.data_default AS valor_predeterminado, 
				'' AS secuencia,
				c_pk.table_name AS fk_tabla, 
				a_pk.column_name AS fk_campo, 
				atc.column_id AS orden,
				atc.table_name AS tabla, 
				DECODE ( (SELECT COUNT(1) 
					    FROM all_cons_columns a1
					    JOIN all_constraints c1 ON a1.owner = c1.owner AND a1.constraint_name = c1.constraint_name
					    WHERE c1.constraint_type = 'P' 
					    AND a1.table_name = atc.table_name 
					    AND a1.owner = atc.owner 
					    AND atc.column_name = a1.column_name), 1 , 't', 'f') AS pk, 
				DECODE ( (SELECT COUNT(1) 
					    FROM all_cons_columns a1
					    JOIN all_constraints c1 ON a1.owner = c1.owner AND a1.constraint_name = c1.constraint_name
					    WHERE c1.constraint_type = 'U' 
					    AND a1.table_name = atc.table_name 
					    AND a1.owner = atc.owner 
					    AND atc.column_name = a1.column_name), 1 , 't', 'f') AS uk
		       FROM SYS.all_tab_cols atc
		       LEFT OUTER JOIN (all_cons_columns a
					INNER JOIN all_constraints c ON a.owner = c.owner AND a.constraint_name = c.constraint_name
					INNER JOIN all_constraints c_pk ON c.r_owner = c_pk.owner AND c.r_constraint_name = c_pk.constraint_name
					INNER JOIN all_cons_columns a_pk ON a_pk.owner = c_pk.owner AND a_pk.constraint_name = c_pk.constraint_name AND a_pk.POSITION = a.position)
					ON (c.constraint_type = 'R' AND a.table_name = atc.table_name AND a.owner = atc.owner AND atc.column_name = a.column_name)
		       WHERE atc.table_name=$tabla_sana
		       $where
		       ORDER BY atc.column_id";
		
		$columnas = $this->consultar($sql);
		if(!$columnas){
			throw new toba_error("La tabla '$tabla' no existe");	
		}
		//2) Normalizo VALORES
		$columnas_booleanas = array('uk','pk','not_null','tiene_predeterminado');
		
		foreach(array_keys($columnas) as $id) 
		{
			//Estas columnas manejan string en vez de booleanos
			foreach($columnas_booleanas as $x) {
				if($columnas[$id][$x]=='t'){
					$columnas[$id][$x] = true;
				}else{
					$columnas[$id][$x] = false;
				}
			}
			//Tipo de datos generico
			$columnas[$id]['tipo'] = $this->get_tipo_datos_generico($columnas[$id]['tipo']);
			//longitudes
			//-- Si el tipo es -1 es que es 'varlena' http://www.postgresql.org/docs/7.4/static/catalog-pg-type.html
			//-- Para el caso de varchar hay que restarle 4
			/*if($columnas[$id]['tipo_longitud'] <= 0){
				$columnas[$id]['longitud'] = $columnas[$id]['longitud'] - 4;
			}*/
			//-- Si es numerico(a,b) la longitud es 327680*b+a, pero para facilitar el proceso general se usa -1
			if ($columnas[$id]['tipo'] == 'numeric') {
				$columnas[$id]['longitud'] = -1;
			}
			//Secuencias
			if($columnas[$id]['tiene_predeterminado']){
				$match = array();
				if(preg_match("&nextval.*?(\'|\")(.*?[.]|)(.*)(\'|\")&",$columnas[$id]['valor_predeterminado'],$match)){
					$columnas[$id]['secuencia'] = $match[3];
				}			
			}
		}
		$this->cache_metadatos[$tabla] = array_values($columnas);
		return $this->cache_metadatos[$tabla];
	}

	function get_semantica_valor_defecto()
	{
		return 'NULL';
	}

	/**
	 * Devuelve el nombre de la columna que es una secuencia en la tabla $tabla del schema $schema.
	 * Si no se especifica el schema se utiliza el que tiene por defecto la base
	 * @return string nombre de la columna si la tabla tiene secuencia. Sino devuelve null
	 */
	/* No implementado en Oracle
	function get_secuencia_tabla($tabla, $schema = null)
	{
	}
	*/
	
	/* No implementado en Oracle
	function get_secuencia_tablas($tablas, $schema = null)
	{
	}
	*/
	
	/**
	 *  Devuelve una lista de los triggers en el esquema, segun estado, nombre y tablas.
	 * @param string $schema	Nombre del schema
	 * @param string $nombre	Comienzo del nombre de/los triggers
	 * @param char $estado		Estado de disparo actual del trigger (O=Origen, D=Disable, A=Always, R=Replica)
	 * @param array $tablas		Tablas involucradas con los triggers
	 * @return array
	 */
/*	function get_triggers_schema($schema, $nombre = '', $estado = 'O', $tablas = array())
	{
		$where = array();
		$esquema = $this->quote($schema);
		$estado = $this->quote($estado);
		$sql = "  SELECT t.*, 
					     c.relname as tabla, 
				              n.nspname as schema
				FROM pg_trigger as t,
				            pg_class as c, 
					   pg_namespace as n 
				WHERE  
					  t.tgrelid = c.oid 
					  AND c.relnamespace = n.oid 
					  AND n.nspname = $esquema
					  AND t.tgenabled = $estado ";
		
		if (trim($nombre) != '') {
			$sql .= ' AND t.tgname ILIKE '. $this->quote($nombre.'%');
		}		
		if (! empty($tablas)) {
			$tablas = $this->quote($tablas);
			$sql  .= ' AND C.relname IN ('. implode(',', $tablas) . ')';
		}
		return $this->consultar($sql);
	}
	
	//-----------------------------------------------------------------------------------
	//-- UTILIDADES PG_DUMP
	//-----------------------------------------------------------------------------------

	/**
	 * Devuelve una tabla del sistema como un arreglo de INSERTS obtenida a partir
	 * del pg_dump de postgres
	 * @param string $bd	El nombre de la base
	 * @param string $schema	El schema al que pertenece la tabla
	 * @param string $tabla		La tabla a exportar
	 * @param string $host		
	 * @param string $usuario
	 * @param string $pass
	 * @return array
	 */
/*	function pgdump_get_tabla($bd, $schema, $tabla, $host, $usuario, $pass = null)
	{
		$exito = 0;
		$comando = "pg_dump  -a -i -d -t $schema.$tabla -h $host -U $usuario $bd";
		$tabla = array();

		if (!is_null($pass)) {
			putenv("PGPASSWORD=$pass");
		}
		
		exec($comando, $tabla, $exito);
		if ($exito > 0) {
			throw new toba_error("Error ejecutando pg_dump. Comando ejecutado: $comando");
		}

		$this->pgdump_limpiar($tabla);
		return $tabla;
	}

	protected function pgdump_limpiar(&$array)
	{
		$borrando = true;

		foreach ($array as $key => $elem) {
			if (comienza_con($elem, 'INSERT')) {
				continue;
			}
			unset($array[$key]);
		}
	}

	//-----------------------------------------------------------------------------------
	//-- AUDITORIA (se le pide una instancia de manejador a la base que ya sabe el motor)
	//-----------------------------------------------------------------------------------

	/**
	 * Devuelve una instancia del manejador de auditoria para este motor de base de datos
	 * ventana de extension en los hijos
	 * @param string $schema_modelo
	 * @param string $schema_auditoria
	 * @param string $schema_toba
	 * @return object
	 */
/*	function get_manejador_auditoria($schema_modelo ='public', $schema_auditoria = 'public_auditoria', $schema_toba = null)
	{
		return new toba_auditoria_tablas_postgres($this, $schema_modelo, $schema_auditoria, $schema_toba);
	}

*/ 
	public function ejecutar_store_procedure($sql, $parametros) 
	{
	    // preparo la sentencia a ejecutar
	    $sentencia = $this->conexion->prepare($sql);
	    
	    // recorro los parametros del store procedure
	    foreach ($parametros as $clave => $parametro) {
			if (isset($parametro['nombre']) && !empty($parametro['nombre']) && isset($parametros[$clave]['valor']) && isset($parametro['tipo_dato']) && !empty($parametro['tipo_dato']) && isset($parametro['longitud']) && !empty($parametro['longitud'])) {
				// agrego los parametros al store procedure
				$sentencia->bindParam(':'.$parametro['nombre'], $parametros[$clave]['valor'], $parametro['tipo_dato'], $parametro['longitud']);
			}
	    }
	    
	    // ejecuto el store procedure
	    $sentencia->execute();
	    // retorno los parametros
	    return $parametros;
	    
	}
	
}
?>
