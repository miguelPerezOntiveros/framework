<?php
	require $_SERVER["DOCUMENT_ROOT"].'/start_settings.inc.php';
	$conn = new mysqli($db_host, $db_user, $db_pass, $config['_projectName'], $db_port);
	if ($conn->connect_errno)
		exit( json_encode((object) ['error' => 'Failed to connect to MySQL: ('.$conn->connect_errno.')'.$conn->connect_error]));
?>