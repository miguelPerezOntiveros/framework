<?php
	$dir = '../projects/'.$config['_name'].'/admin/exports/';

	// rm
	$command = 'cd '.$dir.' && rm -rf '.$row['file'];
	error_log("\n -- Command Delete".$command."\n");
	exec($command);
?>