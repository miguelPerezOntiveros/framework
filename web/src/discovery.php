<?php
	error_reporting(E_ALL ^ E_NOTICE);
	preg_match('/\/projects\/(.*?)\/admin\/discovery\.php/', $_SERVER['REQUEST_URI'], $matches);

	$project = $matches[1];
	require 'load_config.php';
	require 'session.inc.php';

	foreach($config as $table_name => $table){
		if($table_name[0] == '_')
			continue;
		foreach($table as $column_name => $column){
			if($column_name == '_permissions'){
				if($column['create'] == '-' || preg_match('/'.$column['create'].'/', $_SESSION['type']) )	
					$res[$table_name]['create'] = null;
				if($column['read'] == '-' || preg_match('/'.$column['read'].'/', $_SESSION['type']) )	
					$res[$table_name]['read'] = null;
				if($column['update'] == '-' || preg_match('/'.$column['update'].'/', $_SESSION['type']) )	
					$res[$table_name]['update'] = null;
				if($column['delete'] == '-' || preg_match('/'.$column['delete'].'/', $_SESSION['type']) )	
					$res[$table_name]['delete'] = null;
			}
			if($column_name[0] == '_')
				continue;
			if($column['permissions_create'] == '-' || preg_match('/'.$column['permissions_create'].'/', $_SESSION['type']) )	
				$res[$table_name][$column_name][] = 'create';
			if($column['permissions_read'] == '-' || preg_match('/'.$column['permissions_read'].'/', $_SESSION['type']) )	
				$res[$table_name][$column_name][] = 'read';
			if($column['permissions_update'] == '-' || preg_match('/'.$column['permissions_update'].'/', $_SESSION['type']) )	
				$res[$table_name][$column_name][] = 'update';
			if($column['permissions_delete'] == '-' || preg_match('/'.$column['permissions_delete'].'/', $_SESSION['type']) )	
				$res[$table_name][$column_name][] = 'delete';
		}
	}
	
	echo json_encode($res);
?>