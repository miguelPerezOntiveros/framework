<?php
	// TODO make this downloadable
	foreach ($data as $key => $value) {
		$data[$key][4] = '<a href=\'../'.$value[4].'\'>'.$value[4].'</a>';
	}
	
	$columns[4]->display = 'html';
?>