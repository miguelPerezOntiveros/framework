<?php
	// Target folder
	$dir = '../projects/'.$config['_name'].'/admin/uploads/import';

	// Validate permissions for table selection
	$validated_selection = json_decode($row['selection']);
	foreach($validated_selection as $selection) {
		if(
			$selection == 'Extentions Folder' && !preg_match('/System Administrator/', $_SESSION['type']) ||
			$selection != 'Extentions Folder' && $config[$selection]['_permissions']['read'] != '-' && !preg_match('/'.$config[$selection]['_permissions']['read'].'/', $_SESSION['type'])
		)
			$validated_selection = array_diff($validated_selection, [$selection]);
	}
	$row['selection'] = json_encode($validated_selection);
	$validated_table_selection = array_diff($validated_selection, ['Extentions Folder', 'Select All']);

	// Unzip and Import
	$command = './../../import_content.sh '.$dir.' '.$row['file'].' "'.implode(' ', $validated_table_selection).'"| mysql -h '.$db_host.' -P '.$db_port.' -u '.$db_user.' --password='.$db_pass.' --database '.$config['_name'];
	error_log("\n -- Command Unzip and Import: ".$command."\n");
	exec($command);
?>