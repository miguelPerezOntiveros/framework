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
	<!-- Installing PECL yaml
		curl -O http://pear.php.net/go-pear.phar
		sudo php -d detect_unicode=0 go-pear.phar
		sudo pecl channel-update pecl.php.net
		xcode-select --install
		brew install autoconf
		pecl install yaml
		extension=yaml.so >> /private/etc/php.ini
	-->
	<!-- Running php server 
		php -S localhost:8080
	-->
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
				<h2>Input</h2>
				<form action="" method="POST">
					<textarea name="yaml" rows="30" style="width: 100%"></textarea>
					<br><br>
					<button class="btn btn-default">Import</button>
					<button class="btn btn-default">Export</button>
					<button style="float: right;" type="submit" class="btn btn-primary">Submit</button>
				</form>
			</div>

			<div class="col-md-6">
				<?php
					function write($fileName, $contents) {
						global $config;
						$dir = 'projects/'.$config['projectName'].'/admin';
						if (!is_dir($dir))
						    mkdir($dir, 0755, true);
						$fileName = $dir.'/'.$fileName;
						$file = fopen($fileName, 'w') or die('Cannot open file');
						fwrite($file, $contents);
						return $fileName;
					}
					if($_POST['yaml']) {
						//GLOBAL
						$config = yaml_parse($_POST['yaml']);

						//CONF INTERPRETATION
					   	//TODO: pass this to a shorter format.
						if($config['tables']['user_types'] == null){
							$config['tables']['user_types'] = array();
							$config['tables']['user_types']['columns'] = array();
							$config['tables']['user_types']['columns']['name'] = array();
							$config['tables']['user_types']['columns']['name']['permisions'] = 'System Administrator';
							$config['tables']['user_types']['columns']['name']['type'] = '255';
						}
						if($config['tables']['users'] == null){
							$config['tables']['users'] = array();
							$config['tables']['users']['columns'] = array();
							$config['tables']['users']['columns']['user'] = array();
							$config['tables']['users']['columns']['user']['permisions'] = 'System Administrator';
							$config['tables']['users']['columns']['user']['type'] = '255';
							$config['tables']['users']['columns']['pass'] = array();
							$config['tables']['users']['columns']['pass']['permisions'] = 'System Administrator';
							$config['tables']['users']['columns']['pass']['type'] = '255';
							$config['tables']['users']['columns']['type'] = array();
							$config['tables']['users']['columns']['type']['permisions'] = 'System Administrator';
							$config['tables']['users']['columns']['type']['type'] = 'user_types';
						}
						echo "<h2>Interpretation</h2>";
						echo $config['projectName']."<br>";						
						foreach ($config['tables'] as $table => $value) {
							echo "&nbsp;&nbsp;&nbsp;&nbsp;".$table."<br>";
							foreach ($config['tables'][$table]['columns'] as $column => $value) {
								echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$column." | ".
								$config['tables'][$table]['columns'][$column]['type']." | ".
								$config['tables'][$table]['columns'][$column]['permisions']."<br>";
							}
					   	}
						//TODO: write php conf representation to $config['projectName']/conf.php
						
						//TODO: tables/index.php should be copied from table_view.php, which hasn´t been created. crud read is a service.
						//TODO: add inputs for host, user and pass
						//TODO: how will we handle sessions? with a prefix/postfix?
						//TODO: db tables in singular
						//TODO src/db_connection.php
						//TODO src/crud_create.php
						//TODO src/crud_read.php
						//TODO src/crud_update.php
						//TODO src/crud_delete.php
						//TODO src/session.php
						//TODO src/logo.png
						
						//TODO: Develop Files in src:
							//db connection.php
							// crud_create.php
							// crud_read.php
							// crud_update.php
							// crud_delete.php
							//logo.png
							//session.php

						//SQL
						$sql = 'DROP DATABASE IF EXISTS '.$config['projectName'].';'.PHP_EOL;
						$sql .= 'CREATE DATABASE '.$config['projectName'].';'.PHP_EOL;
						$sql .= 'USE '.$config['projectName'].';'.PHP_EOL;
						foreach ($config['tables'] as $table => $value) {
							$sql .= 'CREATE TABLE IF NOT EXISTS '.$table.'(id int NOT NULL AUTO_INCREMENT, ';
							foreach ($config['tables'][$table]['columns'] as $column => $value) {
								$type = $config['tables'][$table]['columns'][$column]['type'];

								if($type ==	 '*')
									$type = 'varchar(255)';
								else if($config['tables'][$type] != null)
									$type = 'int, foreign key('.$column.') references '.$type.'(id)';
								else if(is_numeric($type))
									$type = 'varchar('.$type.')';
								
								$sql .= $column.' '.$type.', ';
							}
							$sql .= 'primary key(id));'.PHP_EOL;
						}
						$sql .= "INSERT INTO user_types(name) VALUES ('System Administrator');".PHP_EOL;
						$sql .= "INSERT INTO user_types(name) VALUES ('User');".PHP_EOL;
						$sql .= "INSERT INTO users(user, pass, type ) VALUES ('admin',  'admin', 1);".PHP_EOL;
						$db_file_name = write('../'.$config['projectName'].'.sql.txt', $sql);
						echo "<h2>SQL</h2>";
						echo "<pre>".$sql."</pre>";
						echo "<a href='$db_file_name'>$db_file_name</a>";

						//RUN SCRIPT
						echo "<h2>Build</h2>";
						$command = './build.sh '.$config['projectName'].' host user pass';
						foreach ($config['tables'] as $table => $value) {
							$command .= ' '.$table;
						}
						echo "<pre>".exec($command)."</pre>";						
					}
				?>
			</div>
		</div>
	</div>
	<script>
		$.get('default.yml', function(data){
			$("textarea[name='yaml']").text(data);
		})
	</script>
</body>
</html>