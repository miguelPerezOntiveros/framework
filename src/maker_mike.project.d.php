<?php
	/*
		delete folder
		delete database;
	*/
		$project = json_decode($row['config'])->_projectName;
		exec('rm -r ../projects/'.$project);
		exec('mysql -h '.$db_host.' -P '.$db_port.' -u '.$db_user.' --password='.$db_pass.' -e "DROP DATABASE IF EXISTS '.$project.';"');
?>