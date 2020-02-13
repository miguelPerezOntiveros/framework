<?php
	// Target folder
	$dir = '../projects/'.$config['_name'].'/admin/uploads/import';

	// Unzip
	$command = 'cd '.$dir.' && unzip '.$row['file'];
	error_log("\n -- Command Unzip: ".$command."\n");
	exec($command);

	// Import
	// TODO pass in $row['selection'] to choose the tables/extentions/themes
	// Validate permissions for table selection
	// $validated_selection = json_decode($row['selection']);
	// foreach($validated_selection as $selection) {
	// 	if(
	// 		$selection == 'Extentions Folder' && !preg_match('/System Administrator/', $_SESSION['type']) ||
	// 		$selection != 'Extentions Folder' && $config[$selection]['_permissions']['read'] != '-' && !preg_match('/'.$config[$selection]['_permissions']['read'].'/', $_SESSION['type'])
	// 	)
	// 		$validated_selection = array_diff($validated_selection, [$selection]);
	// }
	// $row['selection'] = json_encode($validated_selection);
	// $validated_table_selection = array_diff($validated_selection, ['Extentions Folder', 'Select All']);

	$command = './../../import_content.sh '.$dir.'/'.substr($row['file'], 0, -4).' | mysql -h '.$db_host.' -P '.$db_port.' -u '.$db_user.' --password='.$db_pass.' --database '.$config['_name'];
	error_log("\n -- Command Import: ".$command."\n");
	exec($command);

	// delete unzipped
	$command = 'cd '.$dir.' && unzip '.$row['file'];
	error_log("\n -- Command Delete unzipped: ".$command."\n");
	//exec($command);
?>