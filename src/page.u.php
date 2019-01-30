<?php
	$row_new = $row;
	$row = $row_old;
	require 'page.d.php';
	
	$row = $row_new;
	require 'page.c.php';
?>