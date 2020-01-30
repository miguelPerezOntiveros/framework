<?php
	error_reporting(E_ALL ^ E_NOTICE);
	$_GET['project'] = 'maker_mike';
	require 'set_config_and_params.inc.php';
	require_once 'session.inc.php';
	require $_SERVER["DOCUMENT_ROOT"].'/start_settings.inc.php';

	if($_SESSION['type'] != 'System Administrator'){
		echo json_encode((object) ["error" => 'This feature is only for System Administrators']);
		exit();
	}

	// Target folder
	$dir = '../projects/maker_mike/admin/exports/';
	$target_folder = 'import';
	for($now = ''; file_exists($dir.$target_folder.'.zip'); $now = (!$now? time(): $now+1))
		$target_folder = 'import'.$now;

	if ($_FILES['import_file']["size"] > 10*1024*1024)
		exit(json_encode((object) ["error" => "File too large"]));

	error_log('About to move uploaded file: '.json_encode($_FILES['import_file']));
	if (!move_uploaded_file($_FILES['import_file']["tmp_name"], $dir.$target_folder.'.zip')){
		error_log('Error during transfer: '. json_encode($_FILES['import_file']));
		exit(json_encode((object) ["error" => "Error during transfer, check the log"]));
	}
	// TODO check if there are no malitious files

	// Unzip
	$command = 'cd '.$dir.' && unzip '.$target_folder.'.zip && rm -rf '.$target_folder.'.zip';
	error_log('Command: '.$command);
	exec($command);

	$myfile = fopen($dir,$target_folder.'/name.txt', "r") or die("Unable to open file!");
	$to_import = fgets($myfile);
	fclose($myfile);

	if(file_exists($dir.$target_folder.'/db.sql')){
		$config['_name'] = 'maker_mike'; // force db_connection.inc.php to connect to the 'maker_mike' DB
		require 'db_connection.inc.php';
		$sql = 'SELECT config FROM project WHERE JSON_EXTRACT(config, "$.name") = ?;';	
		error_log('SQL - ' .$sql);
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$to_import]);
		if($stmt->fetch(PDO::FETCH_NUM)){
			// TODO	delete project
		}
		// TODO create Maker Mike project
		//	copy pages
		//	copy themes
		//	copy extentions
		// 	// Extentions
		// 	$command = 'cd '.$dir.' && cp -R ../ext '.$target_folder.'/root/admin';
		// 	error_log('Command: '.$command);
		// 	exec($command);
		// }
	}
	else { // it's a data-only import
		$command = './../../import_content.sh target_file | mysql -h '.$db_host.' -P '.$db_port.' -u '.$db_user.' --password='.$db_pass;
		error_log('Command: '.$command);
		exec($command);
		//	copy uploads
		//	copy pages
		//	copy themes
	}

	echo json_encode((object) ['done' => 'done']);
?>