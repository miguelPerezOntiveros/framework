<?php
	$row_new = $row;
	$row = $row_old;
	require '-.theme.d.php';
	
	$row = $row_new;
	if($row['file'] == '')
		$row['file'] = $row_old['file'];
	require '-.theme.c.php';
?>