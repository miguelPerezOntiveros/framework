<?php	
	if(!$_GET['project'])
		$config_path = 'config.inc.php';
	else if($_GET['project'] == 'mike_maker')
		$config_path = '../config.inc.php';
	else 
		$config_path = $_SERVER["DOCUMENT_ROOT"].'/projects/'.$_GET['project'].'/admin/config.inc.php';
	
	if(!file_exists($config_path)){	
		error_log('No config at '.$config_path);
		exit(json_encode((object) ["error" => "No config for this project."]));
	}
	require_once $config_path;
?>