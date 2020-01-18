<?php
	error_reporting(E_ALL ^ E_NOTICE); 
	require 'set_config_and_params.inc.php';
	require_once 'session.inc.php';
	require $_SERVER["DOCUMENT_ROOT"].'/start_settings.inc.php';

	if($_SESSION['type'] != 'System Administrator'){
		echo json_encode((object) ["error" => 'This feature is only for System Administrators']);
		exit();
	}

	// Folder and SQL Dump
	$dir = '../projects/'.$config['_name'].'/admin/exports/';
	$target_folder = $config['_name'];
	for($now = ''; file_exists($dir.$target_folder.'.zip'); $now = (!$now? time(): $now+1))
		$target_folder = $config['_name'].$now;
	$command = 'cd '.$dir.' && mkdir -p '.$target_folder.'/root/admin && mysqldump -h '.$db_host.' -u '.$db_user.' --password='.$db_pass.' --databases '.$config['_name'].' > '.$target_folder.'/db.sql';
	error_log('Command: '.$command);
	exec($command);
	
	// Project Config
	$to_export = $config['_name'];
	$config['_name'] = 'maker_mike'; // force db_connection.inc.php to connect to the 'maker_mike' DB
	require 'db_connection.inc.php';
	$export_config = array();
	$sql = 'SELECT config FROM project WHERE JSON_EXTRACT(config, "$.name") = ?;';	
	error_log('SQL - ' .$sql);
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$to_export]);
	$export_config = $stmt->fetch(PDO::FETCH_NUM);
	file_put_contents($dir.$target_folder.'/config.json', $export_config);

	/* TODO
		get file columns by procesing json_decode($export_config)

		foreach file column
			./scrape.sh db.sql [table_name] [column_index] admin/uploads/[table_name]/ >> files_to_export.txt

		./scrape.sh db.sql page 3 >> files_to_export.txt

		foreach line of file.txt
			cp $line $target_folder/root/$line

	*/

	// Zip
	$command = 'cd '.$dir.' && cp -R ../ext '.$target_folder.'/root/admin && zip '.$target_folder.'.zip -r '.$target_folder.' && rm -rf '.$target_folder;
	error_log('Command: '.$command);
	exec($command);

	echo json_encode((object) ['file' => $target_folder.'.zip', 'path' => $dir]);
?>