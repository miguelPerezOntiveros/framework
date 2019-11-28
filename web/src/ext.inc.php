<?php
	$ext1 = $_SERVER["DOCUMENT_ROOT"].'/projects/'.$_GET['project'].'/admin/ext/'.$_GET['table'].'.'.$postfix.'.php';
	$ext2 = $_SERVER["DOCUMENT_ROOT"].'/src/ext/-.'					.$_GET['table'].'.'.$postfix.'.php';
	$ext3 = $_SERVER["DOCUMENT_ROOT"].'/src/ext/'.$_GET['project'].'.'	.$_GET['table'].'.'.$postfix.'.php';

	if(file_exists($ext1)){
		error_log($_SERVER['REQUEST_URI'].' requiring project specific ext: '.$ext1);
		require $ext1;
	}
	else if(file_exists($ext2)){
		error_log($_SERVER['REQUEST_URI'].' requiring ext from src/ext (unspecified project): '.$ext2);
		require $ext2;
	}
	else if(file_exists($ext3)){
		error_log($_SERVER['REQUEST_URI'].' requiring ext from src/ext (specific project): '.$ext3);
		require $ext3;
	}
?>