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
					<textarea name="json" rows="30" style="width: 100%"></textarea>
					<textarea name="yaml" rows="30" style="width: 100%" hidden></textarea>
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
						$config =  json_decode($_POST['json'], true);

						//var_dump($config);

						//CONF INTERPRETATION
					   	//TODO: pass this to a shorter format.
						if(!isset($config['user_type'])){
							$config['user_type'] = array();
							$config['user_type']['name'] = array();
							$config['user_type']['name']['permissions_read'] = '/System Administrator/';
							$config['user_type']['name']['permissions_update'] = '/System Administrator/';
							$config['user_type']['name']['permissions_create'] = '/System Administrator/';
							$config['user_type']['name']['type'] = '255';
							$config['user_type']['_permissions'] = array();
							$config['user_type']['_permissions']['create'] = '/System Administrator/';
							$config['user_type']['_permissions']['read'] = '/System Administrator/';
							$config['user_type']['_permissions']['update'] = '/System Administrator/';
							$config['user_type']['_permissions']['delete'] = '/System Administrator/';
							$config['user_type']['_show'] = 'name';
						}
						if(!isset($config['user'])){
							$config['user'] = array();
							$config['user']['user'] = array();
							$config['user']['user']['permissions_read'] = '/System Administrator/';
							$config['user']['user']['permissions_update'] = '/System Administrator/';
							$config['user']['user']['permissions_create'] = '/System Administrator/';
							$config['user']['user']['type'] = '255';
							$config['user']['pass'] = array();
							$config['user']['pass']['permissions_read'] = '/System Administrator/';
							$config['user']['pass']['permissions_update'] = '/System Administrator/';
							$config['user']['pass']['permissions_create'] = '/System Administrator/';
							$config['user']['pass']['type'] = '255';
							$config['user']['type'] = array();
							$config['user']['type']['permissions_read'] = '/System Administrator/';
							$config['user']['type']['permissions_update'] = '/System Administrator/';
							$config['user']['type']['permissions_create'] = '/System Administrator/';
							$config['user']['type']['type'] = 'user_type';
							$config['user']['_permissions'] = array();
							$config['user']['_permissions']['create'] = '/System Administrator/';
							$config['user']['_permissions']['read'] = '/System Administrator/';
							$config['user']['_permissions']['update'] = '/System Administrator/';
							$config['user']['_permissions']['delete'] = '/System Administrator/';
							$config['user']['_show'] = 'user';
						}
						echo "<h2>Interpretation</h2>";
						echo $config['_projectName']."<br>";						
						foreach ($config as $table => $value) {
							if($table[0] == '_')
									continue;
							echo "&nbsp;&nbsp;&nbsp;&nbsp;".$table."<br>";
							foreach ($config[$table] as $column => $value) {
								if($column[0] == '_')
									continue;
								if(!isset($config[$table][$column]['permissions_create']))
									$config[$table][$column]['permissions_create'] = '/.*/';
								if(!isset($config[$table][$column]['permissions_read']))
									$config[$table][$column]['permissions_read'] = '-';
								if(!isset($config[$table][$column]['permissions_update']))
									$config[$table][$column]['permissions_update'] = '/.*/';
								if(!isset($config[$table][$column]['type']))
									$config[$table][$column]['type'] = '255';
								echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$column." | ".
								"t= ".$config[$table][$column]['type']." | ".
								"c= ".$config[$table][$column]['permissions_create']." | ".
								"r= ".$config[$table][$column]['permissions_read']." | ".
								"u= ".$config[$table][$column]['permissions_update']."<br>";
							}
							if(!isset($config[$table]['_permissions']['create']))
								$config[$table]['_permissions']['create'] = '/.*/';
							if(!isset($config[$table]['_permissions']['read']))
								$config[$table]['_permissions']['read'] = '-';
							if(!isset($config[$table]['_permissions']['update']))
								$config[$table]['_permissions']['update'] = '/.*/';
							if(!isset($config[$table]['_permissions']['delete']))
								$config[$table]['_permissions']['delete'] = '/.*/';
							if(!isset($config[$table]['_show']))
								$config[$table]['_show'] = key($config[$table]);
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_permissions create: ".$config[$table]['_permissions']['create']."<br>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_permissions read: ".$config[$table]['_permissions']['read']."<br>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_permissions update: ".$config[$table]['_permissions']['update']."<br>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_permissions delete: ".$config[$table]['_permissions']['delete']."<br>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;show: ".$config[$table]['_show']."<br>";
					   	}
						write('temp/'.$config['_projectName'], 'config.inc.php','<?php $config=unserialize(\''.serialize($config).'\');?>');

						//YAML
						write('temp/'.$config['_projectName'], $config['_projectName'].'.yml', $_POST['yaml']);

						//SQL
						$sql = 'DROP DATABASE IF EXISTS '.$config['_projectName'].';'.PHP_EOL;
						$sql .= 'CREATE DATABASE '.$config['_projectName'].';'.PHP_EOL;
						$sql .= 'USE '.$config['_projectName'].';'.PHP_EOL;
						foreach ($config as $table => $value) {
							if($table[0] == '_')
									continue;
							$sql .= 'CREATE TABLE IF NOT EXISTS '.$table.'(id int NOT NULL AUTO_INCREMENT, ';
							foreach ($config[$table] as $column => $value) {
								if($column[0] == '_')
									continue;
								$type = $config[$table][$column]['type'];
								if($type == '\*') // file type
									$type = 'varchar(255)';
								else if(isset($config[$type])) // type matches the name of a table
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
						write('temp/'.$config['_projectName'], $config['_projectName'].'.sql', $sql);

						echo "<h2>SQL</h2>";
						echo "<pre>".$sql."</pre>";
						$db_file_location = 'projects/'.$config['_projectName']."/".$config['_projectName'].".sql";
						echo "<a href='".$db_file_location."'>".$db_file_location."</a>";
						
						//RUN SCRIPT
						echo "<h2>Build</h2>";
						$command = './build.sh '.$config['_projectName'].' '.$_POST['db_host'].' '.$_POST['db_user'].' "'.$_POST['db_pass'].'"';
						echo "<pre>".exec($command)."</pre>";						
					}
				?>
			</div>
		</div>
	</div>
	<script src="vendor/yamljs/yaml.js"></script>
	<script>
		$.get('default.yml', function(data){
			$("textarea[name='json']").text(data);
		});
		$('#yamlForm').submit(function(e){
			$('textarea[name=yaml]').val( $('textarea[name=json]').val());
			$('textarea[name=json]').val( JSON.stringify(YAML.parse($('textarea[name=json]').val())));
		});
	</script>
</body>
</html>