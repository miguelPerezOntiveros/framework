<?php
	$segments = explode("/", $_SERVER['REQUEST_URI']);
	$url = implode("/", array_slice($segments, 3));
	$projectRoot = implode("/", array_slice($segments, 0, 3));
	require $_SERVER["DOCUMENT_ROOT"].$projectRoot.'/admin/config.inc.php';
	require 'page.php';
 ?>