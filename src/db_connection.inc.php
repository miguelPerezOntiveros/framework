<?php
	require $_SERVER["DOCUMENT_ROOT"].'/start_settings.inc.php';
	/// non-PDO starts
	$conn = new mysqli($db_host, $db_user, $db_pass, $config['_projectName'], $db_port);
	if ($conn->connect_errno)
		exit( json_encode((object) ['error' => 'Failed to connect to MySQL: ('.$conn->connect_errno.')'.$conn->connect_error]));
	/// non-PDO ends
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