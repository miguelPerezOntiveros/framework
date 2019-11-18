<?php
	foreach ($data as $key => $value) {
		$data[$key][2] .= ' <a href ="#" class="copy_json_as_yaml"><i class="far fa-copy"></i></a>';
	}
	
	$columns[2]->display = 'html';
?>