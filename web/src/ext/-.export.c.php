<?php
	$row['date_time'] = date("Y/m/d H:i:s");

	// Target folder
	$dir = '../../projects/'.$config['_name'].'/admin/exports/';
	$target_folder = $config['_name'].(isset($_GET['content_only'])?'_content_only':'');
	for($now = ''; file_exists($dir.$target_folder.'.zip'); $now = (!$now? time(): $now+1))
		$target_folder = $config['_name'].(isset($_GET['content_only'])?'_content_only_':'').$now;
	$to_export = $config['_name'];

	// Validate permissions for table selection
	$validated_selection = json_decode($row['selection']);
	foreach($validated_selection as $selection) {
		if(
			$selection == 'Extentions' && !preg_match('/System Administrator/', $_SESSION['type']) ||
			$config[$selection]['_permissions']['read'] != '-' && !preg_match('/'.$config[$selection]['_permissions']['read'].'/', $_SESSION['type'])
		)
			$validated_selection = array_diff($validated_selection, [$selection]);
	}
	$row['selection'] = json_encode($validated_selection);

	// Folder and tables
	// TODO need to specify table names to export_content.sh
	$command = 'cd '.$dir.' && mkdir '.$target_folder.' && ./../../../../../../export_content.sh '.$db_host.' '.$db_user.' '.$db_pass.' '.$db_port.' '.$config['_name'].' '.$target_folder;
	error_log('Command: '.$command);
	//  exec($command);

	// 	// Uploads folders
	// TODO only copy validated tables
	// 	$command = 'cd '.$dir.' && cp -R ../uploads '.$target_folder.'/_uploads';
	// 	error_log('Command: '.$command);
	// 	exec($command);

	
	// Project Config
	$config['_name'] = 'maker_mike'; // force db_connection.inc.php to connect to the 'maker_mike' DB
	require 'db_connection.inc.php';
	$export_config = array();
	$sql = 'SELECT config FROM project WHERE JSON_EXTRACT(config, "$.name") = ?;';	
	error_log('SQL - ' .$sql);
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$to_export]);
	$export_config = $stmt->fetch(PDO::FETCH_NUM);
	// 	file_put_contents($dir.$target_folder.'/config.json', $export_config);
	$export_config = json_decode($export_config[0], true);
	// return to original $config['_name'] and db connection
	$config['_name'] = $to_export;
	require 'db_connection.inc.php';

	// Pages and File from 'file'-type columns
	// TODO I think I can handle themes as I was handling pages
	// TODO only add 'page 1 ./' if pages were selected on the export
	$file_columns = ['page 1 ./'];
	// TODO foreach validated table
	// 	foreach($export_config['tables'] as $table_key => &$table){
	// 		for($i=0;$i<count($table['columns']); $i++){
	// 			if($table['columns'][$i]['type']=='file')
	// 				$file_columns[]= $table['name'].' '.$i.' admin/uploads/'.$table['name'].'/';
	// 		}
	// 	}
	$command = 'cd ../projects/'.$export_config['name'].' && cp --parents `echo '.implode(' ', $file_columns).' | xargs -n 3 sh ./../../../scrape.sh admin/exports/'.$target_folder.'/db.sql` admin/exports/'.$target_folder.'/root/';
	error_log('Command: '.$command);
	// 	exec($command);

	// 	// Extentions
	// TODO only add extentions if they were selected on the export
	// 	$command = 'cd '.$dir.' && cp -R ../ext '.$target_folder.'/root/admin';
	// 	error_log('Command: '.$command);
	// 	exec($command);
	// }
	// file_put_contents($dir.$target_folder.'/name.txt', $to_export);

	// Zip
	$command = 'cd '.$dir.' && zip '.$target_folder.'.zip -r '.$target_folder.' && rm -rf '.$target_folder;
	error_log('Command: '.$command);
	//exec($command);

	// $row['file'] = $target_folder.'.zip', 'path' => $dir;
?>