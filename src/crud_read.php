<?php
	require 'session.inc.php';
	isset($_GET['table']) || die('No requested table');
	require 'config.inc.php';
	//TODO: Check permission to table
	require 'db_connection.inc.php';

	//TODO: actualy do the select
	$sql = '';
	
	//TODO: generate the JSON output. use a lib?
?>
