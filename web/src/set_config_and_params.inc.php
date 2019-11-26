<?php 
	if(!isset($config)){
		if(isset($_GET['project']))
			$project = $_GET['project'];
		else{
			preg_match('/\/projects\/(.*?)\/(.*)/', $_SERVER['REQUEST_URI'], $project);
			$project = $project[1];
		}
		error_log("project: ".$project);

		//Executing PDO
		$config['_projectName'] = 'maker_mike'; // so db_connection.inc.php connects to the 'maker_mike' DB
		require 'db_connection.inc.php';
		$data = array();
		$columns = array();
		$sql = 'SELECT config FROM project WHERE JSON_EXTRACT(config, "$._projectName") = ?;';	
		error_log('SQL - '.$config['_projectName'].' - ' .$sql."\n");
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$project]);
		if(!$row = $stmt->fetch(PDO::FETCH_NUM))
			exit(json_encode((object) ["error" => "Project has not been set up"]));
		else{
			$config=json_decode($row[0], true);
		}

		require 'db_connection.inc.php';
	} else{
		error_log('invoked from backend');

		if(isset($GET_PARAMS))
			$_GET = $GET_PARAMS;
		if(isset($POST_PARAMS))
			$_POST = $POST_PARAMS;
	}
?>