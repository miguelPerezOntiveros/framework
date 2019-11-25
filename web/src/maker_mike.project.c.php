<?php 
	error_reporting(E_ALL ^ E_NOTICE); 

	if(isset($row['config'])) {
		error_log('engine activated');
		require_once 'db_connection.inc.php';

		$row['config'] =  json_decode($row['config'], true);

		// Check if it already exists
		$already_exists = false;
		$ext_sql = "select SCHEMA_NAME from information_schema.SCHEMATA where SCHEMA_NAME NOT IN('maker_mike');";
		error_log('INFO - sql:' .$ext_sql);
		$stmt = $pdo->prepare($ext_sql);
		$stmt->execute();

		while($ext_row = $stmt->fetch(PDO::FETCH_NUM))
			if($row['config']['_projectName'] == $ext_row[0]){
				error_log('Project "'.$ext_row[0].'" already exists.');
				$already_exists = true;
				exit(json_encode((object) ["error" => 'Project "'.$ext_row[0].'" already exists.']));
			}

		// adding in defaults for $row['config']
		if(!isset($row['config']['_show'])){
			$row['config']['_show'] = ucwords(str_replace("_"," ", $row['config']['_projectName'] ));
		}
		if(!isset($row['config']['page']) && $row['config']['_projectName'] != 'maker_mike'){
			$row['config']['page'] = array(
				'name' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '255'
				),
				'url' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '255'
				),
				'html' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '1024'
				),
				'_permissions' => array(
					'create' => 'System Administrator',
					'read' => 'System Administrator',
					'update' => 'System Administrator',
					'delete' => 'System Administrator'
				),
				'_show' => 'name'
			);
		}
		if(!isset($row['config']['portlet']) && $row['config']['_projectName'] != 'maker_mike'){
			$row['config']['portlet'] = array(
				'name' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '255'
				),
				'query_tables' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'select' => 'tables'
				),
				'query_conditions' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '255'
				),
				'pre' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '1024'
				),
				'template' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '1024'
				),
				'tween' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '1024'
				),
				'post' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '1024'
				),
				'_permissions' => array(
					'create' => 'System Administrator',
					'read' => 'System Administrator',
					'update' => 'System Administrator',
					'delete' => 'System Administrator'
				),
				'_show' => 'name'
			);
		}
		if(!isset($row['config']['theme']) && $row['config']['_projectName'] != 'maker_mike'){
			$row['config']['theme'] = array(
				'name' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '255'
				),
				'url' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '255'
				),
				'file' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '*',
					'ext' => array('zip')
				),
				'contents' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '1023'
				),
				'_permissions' => array(
					'create' => 'System Administrator',
					'read' => 'System Administrator',
					'update' => 'System Administrator',
					'delete' => 'System Administrator'
				),
				'_show' => 'name'
			);
		}
		if(!isset($row['config']['user_type'])){
			$row['config']['user_type'] = array(
				'name' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '255'
				),
				'landing_page' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '255'
				),
				'_permissions' => array(
					'create' => 'System Administrator',
					'read' => 'System Administrator',
					'update' => 'System Administrator',
					'delete' => 'System Administrator'
				),
				'_show' => 'name'
			);
		}
		if(!isset($row['config']['user'])){
			$row['config']['user'] = array(
				'user' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '255'
				),
				'pass' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => '255'
				),
				'type' => array(
					'permissions_read' => 'System Administrator',
					'permissions_update' => 'System Administrator',
					'permissions_create' => 'System Administrator',
					'type' => 'user_type'
				),
				'_permissions' => array(
					'create' => 'System Administrator',
					'read' => 'System Administrator',
					'update' => 'System Administrator',
					'delete' => 'System Administrator'
				),
				'_show' => 'user'
			);
		}
		$imageTables = array(); 				
		foreach ($row['config'] as $table_key => &$table) {
			if($table_key[0] == '_')
				continue;
			foreach ($table as $column_key => &$column) {
				if($column_key[0] == '_')
					continue;
				if($column['type'] == '*' && !in_array($table_key, $imageTables))
					$imageTables[] = $table_key;
				if(!isset($column['_show']))
					$column['_show'] = ucwords(str_replace("_"," ", $column_key));
				if(!isset($column['permissions_create']))
					$column['permissions_create'] = '.*';
				if(!isset($column['permissions_read']))
					$column['permissions_read'] = '-';
				if(!isset($column['permissions_update']))
					$column['permissions_update'] = '.*';
				if(!isset($column['type']))
					$column['type'] = '255';
			}
			if(!isset($table['_permissions']['create']))
				$table['_permissions']['create'] = '.*';
			if(!isset($table['_permissions']['read']))
				$table['_permissions']['read'] = '.*';
			if(!isset($table['_permissions']['update']))
				$table['_permissions']['update'] = '.*';
			if(!isset($table['_permissions']['delete']))
				$table['_permissions']['delete'] = '.*';
			if(!isset($table['_show']))
				$table['_show'] = key($table);
		}
		
		// SQL
		$sql = 'DROP DATABASE IF EXISTS '.$row['config']['_projectName'].';'.PHP_EOL;
		$sql .= 'CREATE DATABASE '.$row['config']['_projectName'].';'.PHP_EOL;
		$sql .= 'USE '.$row['config']['_projectName'].';'.PHP_EOL;
		foreach ($row['config'] as $table_key => &$table) {
			if($table_key[0] == '_')
				continue;
			$sql .= 'CREATE TABLE IF NOT EXISTS '.$table_key.'(id int NOT NULL AUTO_INCREMENT, ';
			foreach ($table as $column_key => &$column) {
				if($column_key[0] == '_')
					continue;
				$type = $column['type'];
				if($type == '*') // file type
					$type = 'varchar(255)';
				if(isset($column['select']))
					$type = 'varchar(511)';
				else if(isset($row['config'][$type])) // type matches the name of a table
					$type = 'int, foreign key('.$column_key.') references '.$type.'(id)';
				else if(is_numeric($type))
					$type = 'varchar('.$type.')';
				else if ($type == 'JSON')
					;
				$sql .= $column_key.' '.$type.', ';
			}
			$sql .= 'primary key(id));'.PHP_EOL;
		}
		$sql .= "INSERT INTO user_type(name, landing_page) VALUES ('System Administrator', 'index.php');".PHP_EOL;
		$sql .= "INSERT INTO user_type(name, landing_page) VALUES ('User', 'index.php');".PHP_EOL;
		$sql .= "INSERT INTO user(user, pass, type ) VALUES ('admin',  'admin', 1);".PHP_EOL;
		$sql .= "INSERT INTO user(user, pass, type ) VALUES ('user',  'user', 2);".PHP_EOL;

		// Run pre script
		echo exec($_SERVER["DOCUMENT_ROOT"].'/../build_pre.sh '.$row['config']['_projectName'].' '.$db_host.' '.$db_user.' "'.$db_pass.'" '.implode(',', $imageTables));

		file_put_contents($_SERVER["DOCUMENT_ROOT"].'/projects/'.$row['config']['_projectName'].'/'.$row['config']['_projectName'].'.sql', $sql);	

		// There was a case in which the post script ran (DB was created) but the insert into project query failed. For the user, the proejct went into limbo mode and the project name got taken. TODO
		// Run post script
		$result_of_post_build = array();
		exec($_SERVER["DOCUMENT_ROOT"].'/../build_post.sh '.$row['config']['_projectName'].' '.$db_host.' '.$db_user.' "'.$db_pass.'" '.$db_port.' 2>&1', $result_of_post_build);
		error_log('result of post build: '.json_encode($result_of_post_build));
		error_log('first line of result: '.$result_of_post_build[1]);
		error_log('ERROR: '.(strpos($result_of_post_build[1], "ERROR") !== false));

		if(isset($result_of_post_build) && strpos($result_of_post_build[1], "ERROR") !== false){
			// what do I need this query for? TODO
			//Executing Query
			$sql = 'INSERT INTO '.$_GET['table'].' ('.implode(', ',array_keys($row)).') VALUES (?';
			for($i = 1; $i<count($row); $i++)
				$sql .= ', ?';
			$sql .= ');';	
			error_log('INFO - sql:' .$sql);
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array_values($row));
			echo json_encode((object) ["error" => 'Invalid configuration.']);

			exit();
		}
		// encode it again now that defaults have been added
		$row['config'] =  json_encode($row['config']);
	}
?>