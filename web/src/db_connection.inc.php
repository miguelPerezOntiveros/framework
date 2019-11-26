<?php
	require $_SERVER["DOCUMENT_ROOT"].'/start_settings.inc.php';
	error_log("This is db_connection.inc.php connecting to ".$config['_projectName'].", called from: ".$_SERVER['REQUEST_URI']."\n");

	try {
		$pdo = new PDO(
		    'mysql:host='.$db_host.';dbname='.$config['_projectName'].';port='.$db_port.';charset=utf8mb4',
		    $db_user, $db_pass
		);
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		error_log('Connection failed: ' . $e->getMessage());
		exit( json_encode((object) ['error' => 'Connection failed: ' . $e->getMessage()]));
	}
?>