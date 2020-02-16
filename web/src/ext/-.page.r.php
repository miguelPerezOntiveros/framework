<?php
	foreach ($data as $key => $row) {
		$data[$key][2] = '<a href=\'../'.$row[2].'\'>'.$row[2].'</a>';
		if(strlen($data[$key][3]) > 300)
			$data[$key][3] = substr($data[$key][3], 0, 140).' [..................] '.substr($data[$key][3], -140);
	}
	
	$columns[2]->display = 'html';
?>