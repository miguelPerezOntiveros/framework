<?php 
	error_reporting(E_ALL ^ E_NOTICE); 

	if(isset($row['config'])) {
		error_log('engine activated');
		require 'db_connection.inc.php';

		$row['config'] = json_decode($row['config'], true);

		// Check if it already exists
		$already_exists = false;
		$ext_sql = "select SCHEMA_NAME from information_schema.SCHEMATA where SCHEMA_NAME NOT IN('maker_mike');";
		error_log('INFO - sql:' .$ext_sql);
		$stmt = $pdo->prepare($ext_sql);
		$stmt->execute();
		while($ext_row = $stmt->fetch(PDO::FETCH_NUM))
			if($row['config']['name'] == $ext_row[0]){
				error_log('Project "'.$ext_row[0].'" already exists.');
				$already_exists = true;
				exit(json_encode((object) ["error" => 'Project "'.$ext_row[0].'" already exists.']));
			}

		$associative_config = transform($row['config']);
		// adds in defaults
		if(!isset($row['config']['show'])) // TODO should I be modifying associative_config instead? 
			$row['config']['show'] = ucwords(str_replace("_"," ", $row['config']['name']));
		
		if(!isset($associative_config['export']) && $associative_config['_name'] != 'maker_mike'){
			$row['config']['tables'][] = array(
				'name' => 'export',
				'columns' => array_values(array(
					'notes',
					array('name' => 'date_time', 'hide_in' => 'form'),
					array('name' => 'selection', 'select' => 'tables'),
					array('name' => 'file', 'hide_in' => 'form')
				))
			);
		}
		if(!isset($associative_config['import']) && $associative_config['_name'] != 'maker_mike'){
			$row['config']['tables'][] = array(
				'name' => 'import',
				'columns' => array_values(array(
					'notes',
					array('name' => 'date_time', 'hide_in' => 'form'),
					array('name' => 'selection', 'select' => 'tables'),
					array('name' => 'file', 'type' => 'file', 'ext' => '["zip"]')
				))
			);
		}
		if(!isset($associative_config['page']) && $associative_config['_name'] != 'maker_mike'){
			$row['config']['tables'][] = array(
				'name' => 'page',
				'columns' => array_values(array(
					'name',
					'url',
					array('name' => 'html', 'type' => 1024)
				))
			);
		}
		if(!isset($associative_config['portlet']) && $associative_config['_name'] != 'maker_mike'){
			$row['config']['tables'][] = array(
				'name' => 'portlet',
				'columns' => array_values(array(
					'name',
					array('name' => 'query_tables', 'select' => 'tables'),
					'query_conditions',
					array('name' => 'pre', 'type' => 1024),
					array('name' => 'template', 'type' => 1024),
					array('name' => 'tween','type' => 1024),
					array('name' => 'post', 'type' => 1024)
				))
			);
		}
		if(!isset($associative_config['theme']) && $associative_config['_name'] != 'maker_mike'){
			$row['config']['tables'][] = array(
				'name' => 'theme',
				'columns' => array_values(array(
					'name',
					'url',
					array('name' => 'file', 'type' => 'file', 'ext' => '["zip"]'),
					array('name' => 'contents', 'type' => 1024)
				))
			);
		}
		if(!isset($associative_config['user_type']) && $associative_config['_name']){
			$row['config']['tables'][] = array(
				'name' => 'user_type',
				'columns' => array_values(array(
					'name',
					'landing_page'
				))
			);
		}
		if(!isset($associative_config['user']) && $associative_config['_name']){
			$row['config']['tables'][] = array(
				'name' => 'user',
				'columns' => array_values(array(
					'user',
					'pass',
					array('name' => 'type', 'type' => 'user_type')
				))
			);
		}
		// unabreviate tables
		foreach ($row['config']['tables'] as $table_key => $table) {
			if(is_string($table))
				$row['config']['tables'][$table_key] = array(
				'name' => $table,
				'columns' => array_values(array(
					'name',
					array('name' => 'value', 'type' => 1024)
				)));
			else if(!isset($table['columns']))
				$row['config']['tables'][$table_key]['columns'] = array_values(array(
					'name',
					array('name' => 'value', 'type' => 1024)
				));
		}
		// unabreviate columns
		foreach ($row['config']['tables'] as $table_key => $table) {
			foreach ($table['columns'] as $column_key => $column) {
				if(is_string($column))
					$row['config']['tables'][$table_key]['columns'][$column_key] = array(
						'name' => $column);
			}
		}
		$imageTables = array(); 				
		foreach ($row['config']['tables'] as $table_key => &$table) {
			//echo '<br><br>Looking at table: '.$table['name'].'<br>';
			foreach ($table['columns'] as $column_key => &$column) {
				if(!isset($column['type']))
					$column['type'] = '255';
				if($column['type'] == 'file' && !in_array($table['name'], $imageTables))
					$imageTables[] = $table['name'];
				//echo 'looking at column: '.$column['name'].'<br>';
				if(!isset($column['_show']))
					$column['show'] = ucwords(str_replace("_"," ", $column['name']));
				if(!isset($column['permissions_create']))
					$column['permissions_create'] = '.*';
				if(!isset($column['permissions_read']))
					$column['permissions_read'] = '-';
				if(!isset($column['permissions_update']))
					$column['permissions_update'] = '.*';
			}
			if(!isset($table['permissions']['create']))
				$table['permissions']['create'] = '.*';
			if(!isset($table['permissions']['read']))
				$table['permissions']['read'] = '.*';
			if(!isset($table['permissions']['update']))
				$table['permissions']['update'] = '.*';
			if(!isset($table['permissions']['delete']))
				$table['permissions']['delete'] = '.*';
			if(!isset($table['show']))
				$table['show'] = $table['columns'][0]['name'];
		}
		$associative_config = transform($row['config']);

		// SQL
		$sql = 'DROP DATABASE IF EXISTS '.$row['config']['name'].';'.PHP_EOL;
		$sql .= 'CREATE DATABASE '.$row['config']['name'].';'.PHP_EOL;
		$sql .= 'USE '.$row['config']['name'].';'.PHP_EOL;
		foreach ($row['config']['tables'] as $table_key => &$table) {
			$sql .= 'CREATE TABLE IF NOT EXISTS '.$table['name'].'(id int NOT NULL AUTO_INCREMENT, ';
			foreach ($table['columns'] as $column_key => &$column) {
				$type = $column['type'];
				if($type == 'file') // file type
					$type = 'varchar(256)';
				if(isset($column['select']))
					$type = 'varchar(512)';
				else if(isset($associative_config[$type])) // type matches the name of a table
					$type = 'int, foreign key('.$column['name'].') references '.$type.'(id)';
				else if(is_numeric($type))
					$type = 'varchar('.$type.')';
				else if ($type == 'JSON')
					;
				$sql .= $column['name'].' '.$type.', ';
			}
			$sql .= 'primary key(id));'.PHP_EOL;
		}
		$sql .= "INSERT INTO user_type(name, landing_page) VALUES ('System Administrator', 'index.php');".PHP_EOL;
		$sql .= "INSERT INTO user_type(name, landing_page) VALUES ('User', 'index.php');".PHP_EOL;
		$sql .= "INSERT INTO user(user, pass, type ) VALUES ('admin',  'admin', 1);".PHP_EOL;
		$sql .= "INSERT INTO user(user, pass, type ) VALUES ('user',  'user', 2);".PHP_EOL;

		// Run pre script
		echo exec($_SERVER["DOCUMENT_ROOT"].'/../build_pre.sh '.$row['config']['name'].' '.$db_host.' '.$db_user.' "'.$db_pass.'" '.implode(',', $imageTables));

		file_put_contents($_SERVER["DOCUMENT_ROOT"].'/projects/'.$row['config']['name'].'/'.$row['config']['name'].'.sql', $sql);	
		// Run post script
		$result_of_post_build = array();
		exec($_SERVER["DOCUMENT_ROOT"].'/../build_post.sh '.$row['config']['name'].' '.$db_host.' '.$db_user.' "'.$db_pass.'" '.$db_port.' 2>&1', $result_of_post_build);
		error_log('result of post build: '.json_encode($result_of_post_build));
		error_log('first line of result: '.$result_of_post_build[1]);
		error_log('ERROR: '.(strpos($result_of_post_build[1], "ERROR") !== false));

		if(isset($result_of_post_build) && strpos($result_of_post_build[1], "ERROR") !== false){
			//Executing Query
			// $sql = 'INSERT INTO '.$_GET['table'].' ('.implode(', ',array_keys($row)).') VALUES (?';
			// for($i = 1; $i<count($row); $i++)
			// 	$sql .= ', ?';
			// $sql .= ');';	
			// error_log('INFO - sql:' .$sql);
			// $stmt = $pdo->prepare($sql);
			// $stmt->execute(array_values($row));
			echo json_encode((object) ["error" => 'Invalid configuration.']);
			exit();
		}
		// encode it again now that defaults have been added
		$row['config'] =  json_encode($row['config']);
	}
?>