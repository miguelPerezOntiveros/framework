<?php
	foreach ($data as $key => $value) {
		$data[$key][2] = '<a href=\'../'.$value[2].'\'>'.$value[2].'</a>';
	}
	
	$columns[2]->display = 'html';
?>