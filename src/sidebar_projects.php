<?php 
	require $_SERVER["DOCUMENT_ROOT"].'/config.inc.php';
	require 'session.inc.php';
	require_once 'db_connection.inc.php';
	$sql = "select SCHEMA_NAME from information_schema.SCHEMATA where SCHEMA_NAME NOT IN('mysql', 'information_schema', 'performance_schema', 'sys', 'maker_mike');";
	error_log($sql);
	$res = [];
	if($result = $conn->query($sql))
		while($row = $result->fetch_array(MYSQLI_NUM))
			$res[] = $row[0];
	echo json_encode($res);
 ?>