<?php 
	error_reporting(E_ALL ^ E_NOTICE); 

	if(isset($_POST['config'])) {
		error_log('engine activated');
		require_once 'db_connection.inc.php';

		$newConfig =  json_decode($_POST['config'], true);

		// Check if it already exists
		$ext_sql = "select SCHEMA_NAME from information_schema.SCHEMATA where SCHEMA_NAME NOT IN('maker_mike');";
		error_log($ext_sql);
		if($ext_result = $conn->query($ext_sql))
			while($ext_row = $ext_result->fetch_array(MYSQLI_NUM))
				if($newConfig['_projectName'] == $ext_row[0]){
					error_log('Project "'.$ext_row[0].'" already exists or has an invalid name.');
					exit(json_encode((object) ["error" => 'Project "'.$ext_row[0].'" already exists or has an invalid name.']));
				}

		// Config
		if(!isset($newConfig['_show'])){
			$newConfig['_show'] = ucwords(str_replace("_"," ", $newConfig['_projectName'] ));
		}
		if(!isset($newConfig['page'])){
			$newConfig['page'] = array(
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
		if(!isset($newConfig['portlet'])){
			$newConfig['portlet'] = array(
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
		if(!isset($newConfig['theme'])){
			$newConfig['theme'] = array(
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
				'_permissions' => array(
					'create' => 'System Administrator',
					'read' => 'System Administrator',
					'update' => 'System Administrator',
					'delete' => 'System Administrator'
				),
				'_show' => 'name'
			);
		}
		if(!isset($newConfig['user_type'])){
			$newConfig['user_type'] = array(
				'name' => array(
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
		if(!isset($newConfig['user'])){
			$newConfig['user'] = array(
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
		foreach ($newConfig as $table_key => &$table) {
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
				$table['_permissions']['read'] = '-';
			if(!isset($table['_permissions']['update']))
				$table['_permissions']['update'] = '.*';
			if(!isset($table['_permissions']['delete']))
				$table['_permissions']['delete'] = '.*';
			if(!isset($table['_show']))
				$table['_show'] = key($table);
		}
		
		// SQL
		$sql = 'DROP DATABASE IF EXISTS '.$newConfig['_projectName'].';'.PHP_EOL;
		$sql .= 'CREATE DATABASE '.$newConfig['_projectName'].';'.PHP_EOL;
		$sql .= 'USE '.$newConfig['_projectName'].';'.PHP_EOL;
		foreach ($newConfig as $table_key => &$table) {
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
				else if(isset($newConfig[$type])) // type matches the name of a table
					$type = 'int, foreign key('.$column_key.') references '.$type.'(id)';
				else if(is_numeric($type))
					$type = 'varchar('.$type.')';
				$sql .= $column_key.' '.$type.', ';
			}
			$sql .= 'primary key(id));'.PHP_EOL;
		}
		$sql .= "INSERT INTO user_type(name) VALUES ('System Administrator');".PHP_EOL;
		$sql .= "INSERT INTO user_type(name) VALUES ('User');".PHP_EOL;
		$sql .= "INSERT INTO user(user, pass, type ) VALUES ('admin',  'admin', 1);".PHP_EOL;
		$sql .= "INSERT INTO user(user, pass, type ) VALUES ('user',  'user', 2);".PHP_EOL;

		// Run pre script
		echo exec($_SERVER["DOCUMENT_ROOT"].'/build_pre.sh '.$newConfig['_projectName'].' '.$db_host.' '.$db_user.' "'.$db_pass.'" '.implode(',', $imageTables));

		// Write files
		file_put_contents($_SERVER["DOCUMENT_ROOT"].'/projects/'.$newConfig['_projectName'].'/admin/config.inc.php', '<?php $config=json_decode(\''.json_encode($newConfig).'\', true);?>');
		file_put_contents($_SERVER["DOCUMENT_ROOT"].'/projects/'.$newConfig['_projectName'].'/'.$newConfig['_projectName'].'.yml', $_POST['yaml']);
		file_put_contents($_SERVER["DOCUMENT_ROOT"].'/projects/'.$newConfig['_projectName'].'/'.$newConfig['_projectName'].'.sql', $sql);	

		// Run post script
		$result_of_post_build = array();
		exec($_SERVER["DOCUMENT_ROOT"].'/build_post.sh '.$newConfig['_projectName'].' '.$db_host.' '.$db_user.' "'.$db_pass.'" '.$db_port.' 2>&1', $result_of_post_build);
		error_log('post: '.json_encode($result_of_post_build));
		error_log('first line: '.$result_of_post_build[1]);
		error_log('ERROR: '.(strpos($result_of_post_build[1], "ERROR") !== false));
		if(strpos($result_of_post_build[1], "ERROR") !== false){
			//Executing Query
			$sql = 'INSERT INTO '.$_GET['table'].' ('.implode(', ',array_keys($row)).') VALUES (\''.implode('\', \'', array_values($row)).'\');';	
			error_log('SQL - '.$config['_projectName'].' - ' .$sql);
			if($result = $conn->query($sql))
				echo json_encode((object) ["error" => 'Invalid configuration.']);
			else
				echo json_encode((object) ["error" => $conn->error]);

			$conn->close();
			exit();
		}
	}
?>