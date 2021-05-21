<?php
/**
 * Tipo de página pensado para pantallas de login, presenta un logo y un pie de página básico
 * 
 * @package SalidaGrafica
 */
class toba_tp_logon extends toba_tp_basico
{
	protected function cabecera_html()
	{
		echo "<!DOCTYPE html>\n";
		//-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/
		echo '<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->';
		echo '<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="es"> <![endif]-->';
		echo '<!--[if IE 8]>    <html class="no-js lt-ie9" lang="es"> <![endif]-->';
		echo '<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->';
		echo "<head>\n";
		echo "<title>".$this->titulo_pagina()."</title>\n";
		$this->encoding();
		$this->plantillas_css();
		$this->estilos_css();
		toba_js::cargar_consumos_basicos();
		
		//-- Rescatar Url a imagen de fondo
		$archivo = toba::nucleo()->toba_instalacion_dir().'/instalacion.ini';
		$ini = parse_ini_file($archivo, true);
		$url = isset($ini['url_homepage_login']) ? $ini['url_homepage_login'] : toba_recurso::imagen_toba('fondo.jpg');
		echo "
			<style>
			  body { 
				background-image:url('". $url. "');
				background-repeat: no-repeat;
				background-attachment: fixed;
				background-size: cover;
			}
			</style>";
		echo "</head>\n";
	}

	function barra_superior()
	{
		echo "
			<style type='text/css'>
				.cuerpo {
					
				}
			</style>
		";
		echo "<div id='barra-superior' class='barra-superior-login'>\n";		
	}	

	function pre_contenido()
	{
		echo "<div class='login-titulo'>". toba_recurso::imagen_proyecto("logo.gif",true);
		echo "<div>versión ".toba::proyecto()->get_version()."</div>";
		echo "</div>";
		echo "\n<div align='center' class='cuerpo'>\n";		
	}

	function post_contenido()
	{
		echo "</div>";		
		echo "<div class='login-pie'>";
		echo "<div>Desarrollado por <strong><a href='http://www.intervan.com.ar' style='text-decoration: none' target='_blank'>Intervan</a></strong></div>
			<div>© 1999-".date('Y')."</div>";
		echo "</div>";
	}
}
?>
