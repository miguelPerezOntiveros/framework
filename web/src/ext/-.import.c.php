<?php
	// Target folder
	$dir = '../projects/'.$config['_name'].'/admin/uploads/import';

	// Unzip
	$command = 'cd '.$dir.' && unzip '.$row['file'];
	error_log("\n -- Command Unzip: ".$command."\n");
	exec($command);

	// Import
	$command = './../../import_content.sh '.$dir.'/'.substr($row['file'], 0, -4).' | mysql -h '.$db_host.' -P '.$db_port.' -u '.$db_user.' --password='.$db_pass.' --database '.$config['_name'];
	error_log("\n -- Command Import: ".$command."\n");
	exec($command);

	// delete unzipped
	$command = 'cd '.$dir.' && unzip '.$row['file'];
	error_log("\n -- Command Delete unzipped: ".$command."\n");
	//exec($command);
?>