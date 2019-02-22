<?php
	$row_new = $row;

	if($row_new['url'] != $row_old['url']){
		$row = $row_old;
		require '-.page.d.php';
		
		$row = $row_new;
		require '-.page.c.php';
	}
?>