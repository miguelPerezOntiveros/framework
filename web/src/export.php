<?php
	error_reporting(E_ALL ^ E_NOTICE); 
	require 'set_config_and_params.inc.php';
	require_once 'session.inc.php';
	require $_SERVER["DOCUMENT_ROOT"].'/start_settings.inc.php';

	if($_SESSION['type'] != 'System Administrator'){
		echo json_encode((object) ["error" => 'This feature is only for System Administrators']);
		exit();
	}

	// Target folder
	$dir = '../projects/'.$config['_name'].'/admin/exports/';
	$target_folder = $config['_name'].(isset($_GET['content_only'])?'_content_only':'');
	for($now = ''; file_exists($dir.$target_folder.'.zip'); $now = (!$now? time(): $now+1))
		$target_folder = $config['_name'].(isset($_GET['content_only'])?'_content_only_':'').$now;
	$to_export = $config['_name'];

	if(isset($_GET['content_only'])){
		// Folder and SQL Dump
		$command = 'cd '.$dir.' && mkdir '.$target_folder.' && ./../../../../../export_content.sh '.$db_host.' '.$db_user.' '.$db_pass.' '.$db_port.' '.$config['_name'].' '.$target_folder;
		error_log('Command: '.$command);
		exec($command);

		// Uploads
		$command = 'cd '.$dir.' && cp -R ../uploads '.$target_folder.'/_uploads';
		error_log('Command: '.$command);
		exec($command);
	} else {
		// Folder and SQL Dump
		$command = 'cd '.$dir.' && mkdir -p '.$target_folder.'/root/admin && mysqldump -h '.$db_host.' -P '.$db_port.' -u '.$db_user.' --password='.$db_pass.' --databases '.$config['_name'].' > '.$target_folder.'/db.sql';
		error_log('Command: '.$command);
		exec($command);
	
		// Project Config
		$config['_name'] = 'maker_mike'; // force db_connection.inc.php to connect to the 'maker_mike' DB
		require 'db_connection.inc.php';
		$export_config = array();
		$sql = 'SELECT config FROM project WHERE JSON_EXTRACT(config, "$.name") = ?;';	
		error_log('SQL - ' .$sql);
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$to_export]);
		$export_config = $stmt->fetch(PDO::FETCH_NUM);
		file_put_contents($dir.$target_folder.'/config.json', $export_config);
		$export_config = json_decode($export_config[0], true);

		$file_columns = ['page 1 ./'];
		foreach($export_config['tables'] as $table_key => &$table){
			for($i=0;$i<count($table['columns']); $i++){
				if($table['columns'][$i]['type']=='file')
					$file_columns[]= $table['name'].' '.$i.' admin/uploads/'.$table['name'].'/';
			}
		}

		// Pages and all 'File' columns
		$command = 'cd ../projects/'.$export_config['name'].' && cp --parents `echo '.implode(' ', $file_columns).' | xargs -n 3 sh ./../../../scrape.sh admin/exports/'.$target_folder.'/db.sql` admin/exports/'.$target_folder.'/root/';
		error_log('Command: '.$command);
		exec($command);

		// Extentions
		$command = 'cd '.$dir.' && cp -R ../ext '.$target_folder.'/root/admin';
		error_log('Command: '.$command);
		exec($command);
	}
	file_put_contents($dir.$target_folder.'/name.txt', $to_export);

	// Zip
	$command = 'cd '.$dir.' && zip '.$target_folder.'.zip -r '.$target_folder.' && rm -rf '.$target_folder;
	error_log('Command: '.$command);
	exec($command);

	echo json_encode((object) ['file' => $target_folder.'.zip', 'path' => $dir]);
?>