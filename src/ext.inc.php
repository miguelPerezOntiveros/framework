<?php
	$ext1 = $_SERVER["DOCUMENT_ROOT"].'/projects/'.$_GET['project'].'/admin/ext/'.$_GET['table'].'.'.$postfix.'.php';
	$ext2 = '-.'.$_GET['table'].'.'.$postfix.'.php';
	$ext3 = $_GET['project'].'.'.$_GET['table'].'.'.$postfix.'.php';

	// error_log('$ext1: '.$ext1);
	// error_log('$ext2: '.$ext2);
	// error_log('$ext3: '.$ext3);

	if(file_exists($ext1)){
		error_log($_SERVER['REQUEST_URI'].' requiring project specific ext: '.$ext1);
		require($ext1);
	}
	else if(file_exists($ext2)){
		error_log($_SERVER['REQUEST_URI'].' requiring ext from src (unspecified project): '.$ext2);
		require($ext2);
	}
	else if(file_exists($ext3)){
		error_log($_SERVER['REQUEST_URI'].' requiring ext from src (specific project): '.$ext3);
		require($ext3);
	}
?>