<?php
	// This should live in maker_mike.ext.js, probably on the doTablePostHook function TODO
	foreach ($data as $key => $value) {
		$data[$key][2] .= ' <a href ="#" onclick="doModal(\'success\', \'YAML copied\', 800); return false;" class="copy_json_as_yaml"><i class="far fa-copy"></i></a>';
	}
	
	$columns[2]->display = 'html';
?>