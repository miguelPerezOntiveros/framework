<?php
	$baseProjectUrl = '../projects/'.$config['_projectName'];

	foreach ($data as $key => $value) {
		$originalFileName = $value[3];
		$data[$key][3] = '<a href="uploads/theme/'.$originalFileName.'">'.$originalFileName.'</a>';
		$data[$key][3] .= ' ('.exec('ls -l "'.$baseProjectUrl.'/admin/uploads/theme/'.$originalFileName.'" | awk \'{print $5, $6, $7, $8}\'').')<br>';
		foreach (json_decode($data[$key][4]) as $file_in_theme) {
			$data[$key][3] .= '<a href="../../'.$config['_projectName'].'/'.$value[2].$file_in_theme.'">'.$value[2].$file_in_theme.'</a><br>';
		}
		unset($data[$key][4]);
	}
	$columns[3]->display = 'html';
	unset($columns[4]);
?>