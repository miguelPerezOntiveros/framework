<?php
	$baseProjectUrl = '../projects/'.$config['_projectName'];

	foreach ($data as $key => $value) {
		$originalFileName = $value[3];
		$data[$key][3] = '<a href="uploads/theme/'.$originalFileName.'">'.$originalFileName.'</a>';
		$data[$key][3] .= ' ('.exec('ls -l '.$baseProjectUrl.'/admin/uploads/theme/'.$originalFileName.' | awk \'{print $5, $6, $7, $8}\'').')<br>';
		
		$execOutput = array();
		exec('cd '.$baseProjectUrl.' && find '.$value[2].' -type f', $execOutput);
		foreach($execOutput as $line) {
			$line = '<a href=\'../'.$line.'\'>'.$line.'</a><br>';
			$data[$key][3] .= $line;
		}
	}
	$columns[3]->display = 'html';
?>