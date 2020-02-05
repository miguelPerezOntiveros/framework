<?php
	$row['date_time'] = date("Y/m/d H:i:s");

	// Target folder
	$dir = '../projects/'.$config['_name'].'/admin/exports/';
	$target_folder = $config['_name'];
	for($now = ''; file_exists($dir.$target_folder.'.zip') || file_exists($dir.$target_folder); $now = (!$now? time(): $now+1))
		$target_folder = $config['_name'].$now;
	$to_export = $config['_name'];

	// Validate permissions for table selection
	$validated_selection = json_decode($row['selection']);
	foreach($validated_selection as $selection) {
		if(
			$selection == 'Extentions' && !preg_match('/System Administrator/', $_SESSION['type']) ||
			$selection != 'Extentions' && $config[$selection]['_permissions']['read'] != '-' && !preg_match('/'.$config[$selection]['_permissions']['read'].'/', $_SESSION['type'])
		)
			$validated_selection = array_diff($validated_selection, [$selection]);
	}
	$row['selection'] = json_encode($validated_selection);
	$validated_table_selection = array_diff($validated_selection, ['Extentions']);

	// Folder and tables
	$command = 'cd '.$dir.' && mkdir '.$target_folder.' && ./../../../../../export_content.sh '.$db_host.' '.$db_user.' '.$db_pass.' '.$db_port.' '.$config['_name'].' "'.implode(' ', $validated_table_selection).'" '.$target_folder;
	error_log("\n -- Command folder and tables: ".$command."\n");
	exec($command);

	//	'_uploads' folder
	$command = 'cd '.$dir.'../uploads && mkdir ../exports/'.$target_folder.'/_uploads && cp -r '.implode(' ', $validated_table_selection).' ../exports/'.$target_folder.'/_uploads';
	error_log("\n -- Command uploads: ".$command."\n");
	exec($command);

	// 	Extentions
	// TODO add all valid tables as options, add Extentions as option, add Select All as option
	if(count(array_diff($validated_selection, $validated_table_selection)) != 0){
		$command = 'cd '.$dir.' && cp -r ../ext '.$target_folder.'/root/admin';
		error_log("\n -- Command exts: ".$command."\n");
	// 	exec($command);
	}
	
	// Project Config
	$config['_name'] = 'maker_mike'; // force db_connection.inc.php to connect to the 'maker_mike' DB
	require 'db_connection.inc.php';
	$export_config = array();
	$sql = 'SELECT config FROM project WHERE JSON_EXTRACT(config, "$.name") = ?;';	
	error_log('SQL - ' .$sql);
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$to_export]);
	$export_config = $stmt->fetch(PDO::FETCH_NUM);
	// file_put_contents($dir.$target_folder.'/config.json', $export_config);
	$export_config = json_decode($export_config[0], true);
	$config['_name'] = $to_export; // reconnect to the requested DB
	require 'db_connection.inc.php';

	// Files from 'file'-type columns (plus Pages)
	$file_columns = [];
	if(in_array('Page', $validated_table_selection))
		$file_columns[] = ['page 1 ./'];

	foreach($export_config['tables'] as $table_key => &$table){
		if(in_array($table_key, $validated_table_selection))
			for($i=0;$i<count($table['columns']); $i++){
				if($table['columns'][$i]['type']=='file')
					$file_columns[]= $table['name'].' '.$i.' admin/uploads/'.$table['name'].'/';
			}
	}
	
	$command = 'cd ../projects/'.$export_config['name'].' && cp --parents `echo '.implode(' ', $file_columns).' | xargs -n 3 sh ./../../../scrape.sh admin/exports/'.$target_folder.'/db.sql` admin/exports/'.$target_folder.'/root/';
	error_log("\n -- Command scrape: ".$command."\n");
	// 	exec($command);

	// Zip
	$command = 'cd '.$dir.' && zip '.$target_folder.'.zip -r '.$target_folder.' && rm -rf '.$target_folder;
	error_log("\n -- Command zip: ".$command."\n");
	//exec($command);

	$row['file'] = $target_folder.'.zip';
?>