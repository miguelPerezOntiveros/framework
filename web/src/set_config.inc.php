<?php 
	if(!isset($project)){
		preg_match('/\/projects\/(.*?)\/(.*)/', $_SERVER['REQUEST_URI'], $project);
		$project = $project[1];
	}
	error_log("project: ".$project);

	//Executing PDO
	$config['_projectName'] = 'maker_mike';
	require_once 'db_connection.inc.php';
	$data = array();
	$columns = array();
	$sql = 'SELECT config FROM project WHERE JSON_EXTRACT(config, "$._projectName") = ?;';	
	error_log('SQL - '.$config['_projectName'].' - ' .$sql);
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$project]);
	while($row = $stmt->fetch(PDO::FETCH_NUM))
		$config=json_decode($row[0], true);
?>