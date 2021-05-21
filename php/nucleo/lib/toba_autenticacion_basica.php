<?php	
class toba_autenticacion_basica extends toba_autenticacion implements toba_autenticable	
{
	function autenticar($id_usuario, $clave, $datos_iniciales=null)
	{
		$parametros = array($id_usuario, $clave, $datos_iniciales); 
		$subclase = toba::proyecto()->get_parametro('usuario_subclase');
		$archivo = toba::proyecto()->get_parametro('usuario_subclase_archivo');
		if (trim($archivo) != '' && trim($subclase) != '') {
			$pm = toba::proyecto()->get_parametro('pm_usuario');
			toba_cargador::cargar_clase_archivo($pm, $archivo, toba::proyecto()->get_id());				$clase = $subclase;
		} else {
			$clase = 'toba_usuario_basico';
		}		
		$estado = call_user_func_array(array($clase, 'autenticar'), $parametros);
		return $estado;
	}
	
	function verificar_clave_vencida($id_usuario)
	{	
		$parametros = array($id_usuario);
		$subclase = toba::proyecto()->get_parametro('usuario_subclase');
		$archivo = toba::proyecto()->get_parametro('usuario_subclase_archivo');
		if (trim($archivo) != '' && trim($subclase) != '') {
			$pm = toba::proyecto()->get_parametro('pm_usuario');
			toba_cargador::cargar_clase_archivo($pm, $archivo, toba::proyecto()->get_id());
			$clase = $subclase;
		} else {
			$clase = 'toba_usuario_basico';
		}		
		$estado = call_user_func_array(array($clase, 'verificar_clave_vencida'), $parametros);
		return $estado;
	}
	
	function logout()
	{	
		//Definicion para completar API.
		$this->eliminar_marca_login(self::$marca_login_basico);
	}
	
	function verificar_logout()
	{
		
	}
}
?>
