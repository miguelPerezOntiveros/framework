<?php
	if($_GET['project'] == 'mike_maker')
		$config_path = '../config.inc.php';
	else
		$config_path = '../projects/'.$_GET['project'].'/admin/config.inc.php';
	if(file_exists($config_path))
		require $config_path;
	else {
		error_log('No config at '.$config_path);
		exit(json_encode((object) ["error" => "No config for this project."]));
	}
?>