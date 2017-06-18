<!DOCTYPE html>
<html lang="en">
<head>
	<title>Framework 1.5</title>
	<meta charset="UTF-8">
	<!-- jQuery -->
	<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>

	<!-- Bootstrap -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="row">
				<br><br>
				<h1>Framework 1.5</h1>
				<br>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<h2>YAML</h2>
				<form action="" method="POST" id="yamlForm">
					<textarea name="yaml" rows="30" style="width: 100%"></textarea>
					<br><br>
					<label for="input[name='db_host']">Database Host:</label><br>
					<input type="text" name="db_host" value="127.0.0.1"/><br>

					<label for="input[name='db_user']">Database User:</label><br>
					<input type="text" name="db_user" value="root"/><br>

					<label for="input[name='db_pass']">Database Password:</label><br>
					<input type="password" name="db_pass"/><br>
					<br>
					<!-- TODO: Import with drag and drop -->
					<!-- TODO: Export should download a yaml file -->
					<button class="btn btn-default">Import</button>
					<button class="btn btn-default">Export</button>
					<button style="float: right;" type="submit" class="btn btn-primary">Submit</button>
				</form><br>
			</div>

			<div class="col-md-6">
				<?php
					error_reporting(E_ALL | E_STRICT);
					ini_set('display_errors', 'On');

					function write($dir, $fileName, $contents) {
						if (!is_dir($dir))
						    mkdir($dir, 0755, true);
						$fileName = $dir.'/'.$fileName;
						file_put_contents($fileName, $contents);
						return $fileName;
					}
					if(isset($_POST['yaml'])) {
						//GLOBAL
						$config =  json_decode($_POST['yaml'], true);

						//CONF INTERPRETATION
					   	//TODO: pass this to a shorter format.
						if(!isset($config['tables']['user_types'])){
							$config['tables']['user_type'] = array();
							$config['tables']['user_type']['columns'] = array();
							$config['tables']['user_type']['columns']['name'] = array();
							$config['tables']['user_type']['columns']['name']['permissions'] = '/System Administrator/';
							$config['tables']['user_type']['columns']['name']['type'] = '255';
							$config['tables']['user_type']['permissions'] = '/System Administrator/';
						}
						if(!isset($config['tables']['users'])){
							$config['tables']['user'] = array();
							$config['tables']['user']['columns'] = array();
							$config['tables']['user']['columns']['user'] = array();
							$config['tables']['user']['columns']['user']['permissions'] = '/System Administrator/';
							$config['tables']['user']['columns']['user']['type'] = '255';
							$config['tables']['user']['columns']['pass'] = array();
							$config['tables']['user']['columns']['pass']['permissions'] = '/System Administrator/';
							$config['tables']['user']['columns']['pass']['type'] = '255';
							$config['tables']['user']['columns']['type'] = array();
							$config['tables']['user']['columns']['type']['permissions'] = '/System Administrator/';
							$config['tables']['user']['columns']['type']['type'] = 'user_type';
							$config['tables']['user']['permissions'] = '/System Administrator/';
						}
						echo "<h2>Interpretation</h2>";
						echo $config['projectName']."<br>";						
						foreach ($config['tables'] as $tableName => $table) {
							echo "&nbsp;&nbsp;&nbsp;&nbsp;".$tableName."<br>";
							foreach ($config['tables'][$tableName]['columns'] as $column => $value) {
								if(!isset($config['tables'][$tableName]['columns'][$column]['permissions']))
									$config['tables'][$tableName]['columns'][$column]['permissions'] = '/.*/';
								echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$column." | ".
								$config['tables'][$tableName]['columns'][$column]['type']." | ".
								$config['tables'][$tableName]['columns'][$column]['permissions']."<br>";
							}
							if(!isset($config['tables'][$tableName]['permissions_create']))
								$config['tables'][$tableName]['permissions_create'] = '/.*/';
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;permissions_create: ".$config['tables'][$tableName]['permissions_create']."<br>";
							if(!isset($config['tables'][$tableName]['permissions_read']))
								$config['tables'][$tableName]['permissions_read'] = '/.*/';
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;permissions_read: ".$config['tables'][$tableName]['permissions_read']."<br>";
							if(!isset($config['tables'][$tableName]['permissions_update']))
								$config['tables'][$tableName]['permissions_update'] = '/.*/';
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;permissions_update: ".$config['tables'][$tableName]['permissions_update']."<br>";
							if(!isset($config['tables'][$tableName]['permissions_delete']))
								$config['tables'][$tableName]['permissions_delete'] = '/.*/';
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;permissions_delete: ".$config['tables'][$tableName]['permissions_delete']."<br>";
							if(!isset($config['tables'][$tableName]['show']))
								$config['tables'][$tableName]['show'] = key($config['tables'][$tableName]['columns']);
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;show: ".$config['tables'][$tableName]['show']."<br>";
					   	}

						write('temp/'.$config['projectName'], 'config.inc.php','<?php $config=unserialize(\''.serialize($config).'\');?>');

						//TODO: Develop Files in src:
							// crud_create.php
							// crud_read.php
							// crud_update.php
							// crud_delete.php
							
						//TODO: should access to reference imply access to referenced column?

					   	//TODO: open project in new tab?
					   	//TODO: encript passwords in db

					   	//TODO: make sure we get feedback if db couldn´t be created. PHP will know if the 3 inputs are not submited.
					   	//TODO: make sure we get feedback on sql errors.

						//SQL
						$sql = 'DROP DATABASE IF EXISTS '.$config['projectName'].';'.PHP_EOL;
						$sql .= 'CREATE DATABASE '.$config['projectName'].';'.PHP_EOL;
						$sql .= 'USE '.$config['projectName'].';'.PHP_EOL;
						foreach ($config['tables'] as $table => $value) {
							$sql .= 'CREATE TABLE IF NOT EXISTS '.$table.'(id int NOT NULL AUTO_INCREMENT, ';
							foreach ($config['tables'][$table]['columns'] as $column => $value) {
								$type = $config['tables'][$table]['columns'][$column]['type'];

								if($type == '\*')
									$type = 'varchar(255)';
								else if(isset($config['tables'][$type]))
									$type = 'int, foreign key('.$column.') references '.$type.'(id)';
								else if(is_numeric($type))
									$type = 'varchar('.$type.')';
								
								$sql .= $column.' '.$type.', ';
							}
							$sql .= 'primary key(id));'.PHP_EOL;
						}
						$sql .= "INSERT INTO user_type(name) VALUES ('System Administrator');".PHP_EOL;
						$sql .= "INSERT INTO user_type(name) VALUES ('User');".PHP_EOL;
						$sql .= "INSERT INTO user(user, pass, type ) VALUES ('admin',  'admin', 1);".PHP_EOL;
						$sql .= "INSERT INTO user(user, pass, type ) VALUES ('user',  'user', 2);".PHP_EOL;
						$db_file_name = write('temp/'.$config['projectName'], $config['projectName'].'.sql', $sql);
						echo "<h2>SQL</h2>";
						echo "<pre>".$sql."</pre>";
						$db_file_location = 'projects/'.$config['projectName']."/".$config['projectName'].".sql";
						echo "<a href='".$db_file_location."'>".$db_file_location."</a>";
						
						//RUN SCRIPT
						echo "<h2>Build</h2>";
						$command = './build.sh '.$config['projectName'].' '.$_POST['db_host'].' '.$_POST['db_user'].' '.$_POST['db_pass'];
						echo "<pre>".exec($command)."</pre>";						
					}
				?>
			</div>
		</div>
	</div>
	<script src="vendor/yamljs/yaml.js"></script>
	<script>
		$.get('default.yml', function(data){
			$("textarea[name='yaml']").text(data);
		});
		$('#yamlForm').submit(function(e){
			$('textarea[name=yaml]').val( JSON.stringify(YAML.parse($('textarea[name=yaml]').val())));
		});
	</script>
</body>
</html>