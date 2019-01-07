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
			<div class="col-md-4">
				<h2>YAML</h2>
				<form action="" method="POST" id="yamlForm" style="border: 1px solid black; padding: 5px;">
					<textarea name="json" rows="50" style="width: 100%"></textarea>
					<textarea name="yaml" rows="50" style="width: 100%" hidden></textarea>
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

			<div class="col-md-8">
				<?php
					// error_reporting(E_ALL | E_STRICT);
					// ini_set('display_errors', 'On');

					if(isset($_POST['yaml'])) {
						$config =  json_decode($_POST['json'], true);

						// Config
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
						$imageTables = array(); 				
						foreach ($config as $table_key => &$table) {
							if($table_key[0] == '_')
								continue;
							echo "&nbsp;&nbsp;&nbsp;&nbsp;".$table_key."<br>";
							foreach ($table as $column_key => &$column) {
								if($column_key[0] == '_')
									continue;
								if($column['type'] == '\*' && !in_array($table_key, $imageTables))
									$imageTables[] = $table_key;
								if(!isset($column['permissions_create']))
									$column['permissions_create'] = '/.*/';
								if(!isset($column['permissions_read']))
									$column['permissions_read'] = '-';
								if(!isset($column['permissions_update']))
									$column['permissions_update'] = '/.*/';
								if(!isset($column['type']))
									$column['type'] = '255';
								echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$column_key." | ".
								"t= ".$column['type']." | ".
								"c= ".$column['permissions_create']." | ".
								"r= ".$column['permissions_read']." | ".
								"u= ".$column['permissions_update']."<br>";
							}
							if(!isset($table['_permissions']['create']))
								$table['_permissions']['create'] = '/.*/';
							if(!isset($table['_permissions']['read']))
								$table['_permissions']['read'] = '-';
							if(!isset($table['_permissions']['update']))
								$table['_permissions']['update'] = '/.*/';
							if(!isset($table['_permissions']['delete']))
								$table['_permissions']['delete'] = '/.*/';
							if(!isset($table['_show']))
								$table['_show'] = key($table);
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_permissions create: ".$table['_permissions']['create']."<br>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_permissions read: ".$table['_permissions']['read']."<br>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_permissions update: ".$table['_permissions']['update']."<br>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_permissions delete: ".$table['_permissions']['delete']."<br>";
							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;show: ".$table['_show']."<br>";
					   	}

					echo "</div>";
					echo '<div class="col-md-12">';
						// SQL
						$sql = 'DROP DATABASE IF EXISTS '.$config['_projectName'].';'.PHP_EOL;
						$sql .= 'CREATE DATABASE '.$config['_projectName'].';'.PHP_EOL;
						$sql .= 'USE '.$config['_projectName'].';'.PHP_EOL;
						foreach ($config as $table_key => &$table) {
							if($table_key[0] == '_')
									continue;
							$sql .= 'CREATE TABLE IF NOT EXISTS '.$table_key.'(id int NOT NULL AUTO_INCREMENT, ';
							foreach ($table as $column_key => &$column) {
								if($column_key[0] == '_')
									continue;
								$type = $column['type'];
								if($type == '\*') // file type
									$type = 'varchar(255)';
								else if(isset($config[$type])) // type matches the name of a table
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

						echo "<h2>SQL</h2>";
						echo "<pre>".$sql."</pre>";
						$db_file_location = 'projects/'.$config['_projectName']."/".$config['_projectName'].".sql";
						echo "<a href='".$db_file_location."'>".$db_file_location."</a>";
						
						// Run _pre script
						echo "<h2>Build</h2>";
						$params = $config['_projectName'].' '.$_POST['db_host'].' '.$_POST['db_user'].' "'.$_POST['db_pass'].'" '.implode(',', $imageTables);
						exec('./build_pre.sh '.$params);

						// Write files
						file_put_contents('projects/'.$config['_projectName'].'/admin/config.inc.php', '<?php $config=unserialize(\''.serialize($config).'\');?>');
						file_put_contents('projects/'.$config['_projectName'].'/'.$config['_projectName'].'.yml', $_POST['yaml']);
						file_put_contents('projects/'.$config['_projectName'].'/'.$config['_projectName'].'.sql', $sql);	

						// Run _post script
						echo "<pre>".exec('./build_post.sh '.$params)."</pre>";
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