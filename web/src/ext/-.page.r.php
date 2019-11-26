<?php
	foreach ($data as $key => $value) {
		$data[$key][1] = '<a href=\'../'.$value[1].'\'>'.$value[1].'</a>';
	}
	
	$columns[1]->display = 'html';
?>