<?php
	$row_new = $row;

	if($row_new['url'] != $row_old['url']){
		error_log("about to update a page\n");
		$row = $row_old;
		require '-.page.d.php';
		
		$row = $row_new;
		require '-.page.c.php';
	}
?>