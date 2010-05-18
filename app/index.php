<?php
	$config = array();
	
	// Path to root of the site.  /Users/danillos/Developer/www/danillocesar
	define('ROOT', dirname(__FILE__));
	
	
	// URL para ser utilizado no lado do cliente.
	$dir = dirname($_SERVER["SCRIPT_NAME"]);
	if($dir === '/') $dir = '';
	define('SITE_URL', "http://".$_SERVER['SERVER_NAME'].$dir);
	
	
	// Constant to insert others views in view.
	define('VIEW', ROOT.'/views/');
	
	
	// Inclui arquivos essencias.
	include(ROOT.'/configs/application.php');
	include(ROOT.'/configs/enviroments/'.ENV.'.php');
	include(CORE.'/Main.php');
	
?>
