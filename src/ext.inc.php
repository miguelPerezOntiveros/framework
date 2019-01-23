<?php
	$ext1 = '../projects/'.$_GET['project'].'/admin/ext/'.$_GET['table'].'.'.$postfix.'.php';
	$ext2 = $_GET['project'].'.'.$_GET['table'].'.'.$postfix.'.php';

	if(file_exists($ext1)){
		error_log('requiring project specific ext: '.$ext1);
		require($ext1);
	}

	else if(file_exists($ext2)){
		error_log('requiring ext from src: '.$ext2);
		require($ext2);
	}
?>