<?php
	// Target folder
	$dir = '../projects/'.$config['_name'].'/admin/uploads/import';

	// Unzip
	$command = 'cd '.$dir.' && unzip '.$row['file'];
	error_log("\n -- Command Unzip: ".$command."\n");
	exec($command);
?>