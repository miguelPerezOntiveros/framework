<?php
	require $_SERVER["DOCUMENT_ROOT"].'/src/set_config_and_params.inc.php';

	$segments = explode("/", $_SERVER['REQUEST_URI']);
	$url = implode("/", array_slice($segments, 3));
	require 'page.php';
 ?>