<?php
		$project = json_decode($row['config'])->_projectName;
		error_log('Evaluating if I should delete project named '.$project);
		if($project == 'maker_mike'){
			echo json_encode((object) ["error" => 'No entries deleted.']);
			exit();
		} else{
			exec('rm -r ../projects/'.$project);
			exec('mysql -h '.$db_host.' -P '.$db_port.' -u '.$db_user.' --password='.$db_pass.' -e "DROP DATABASE IF EXISTS '.$project.';"');
		}
?>