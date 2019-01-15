<?php 	require 'config.inc.php';
require 'src/session.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php require 'src/head.inc.php'; ?>
<body>
	<div class="sidebarWrapper_wrapper">
										<?php
							require_once 'src/db_connection.inc.php';

									// error_reporting(E_ALL | E_STRICT);
									// ini_set('display_errors', 'On');

									if(isset($_POST['yaml'])) {
										$config =  json_decode($_POST['json'], true);

										// Config
										if(!isset($config['page'])){
											$config['page'] = array(
												'name' => array(
													'permissions_read' => '/System Administrator/',
													'permissions_update' => '/System Administrator/',
													'permissions_create' => '/System Administrator/',
													'type' => '255'
												),
												'url' => array(
													'permissions_read' => '/System Administrator/',
													'permissions_update' => '/System Administrator/',
													'permissions_create' => '/System Administrator/',
													'type' => '255'
												),
												'html' => array(
													'permissions_read' => '/System Administrator/',
													'permissions_update' => '/System Administrator/',
													'permissions_create' => '/System Administrator/',
													'type' => '1024'
												),
												'_permissions' => array(
													'create' => '/System Administrator/',
													'read' => '/System Administrator/',
													'update' => '/System Administrator/',
													'delete' => '/System Administrator/'
												),
												'_show' => 'name'
											);
										}
										if(!isset($config['portlet'])){
											$config['portlet'] = array(
												'name' => array(
													'permissions_read' => '/System Administrator/',
													'permissions_update' => '/System Administrator/',
													'permissions_create' => '/System Administrator/',
													'type' => '255'
												),
												'query' => array(
													'permissions_read' => '/System Administrator/',
													'permissions_update' => '/System Administrator/',
													'permissions_create' => '/System Administrator/',
													'type' => '255'
												),
												'pre' => array(
													'permissions_read' => '/System Administrator/',
													'permissions_update' => '/System Administrator/',
													'permissions_create' => '/System Administrator/',
													'type' => '1024'
												),
												'template' => array(
													'permissions_read' => '/System Administrator/',
													'permissions_update' => '/System Administrator/',
													'permissions_create' => '/System Administrator/',
													'type' => '1024'
												),
												'tween' => array(
													'permissions_read' => '/System Administrator/',
													'permissions_update' => '/System Administrator/',
													'permissions_create' => '/System Administrator/',
													'type' => '1024'
												),
												'post' => array(
													'permissions_read' => '/System Administrator/',
													'permissions_update' => '/System Administrator/',
													'permissions_create' => '/System Administrator/',
													'type' => '1024'
												),
												'_permissions' => array(
													'create' => '/System Administrator/',
													'read' => '/System Administrator/',
													'update' => '/System Administrator/',
													'delete' => '/System Administrator/'
												),
												'_show' => 'name'
											);
										}
										if(!isset($config['user_type'])){
											$config['user_type'] = array(
												'name' => array(
													'permissions_read' => '/System Administrator/',
													'permissions_update' => '/System Administrator/',
													'permissions_create' => '/System Administrator/',
													'type' => '255'
												),
												'_permissions' => array(
													'create' => '/System Administrator/',
													'read' => '/System Administrator/',
													'update' => '/System Administrator/',
													'delete' => '/System Administrator/'
												),
												'_show' => 'name'
											);
										}
										if(!isset($config['user'])){
											$config['user'] = array(
												'user' => array(
													'permissions_read' => '/System Administrator/',
													'permissions_update' => '/System Administrator/',
													'permissions_create' => '/System Administrator/',
													'type' => '255'
												),
												'pass' => array(
													'permissions_read' => '/System Administrator/',
													'permissions_update' => '/System Administrator/',
													'permissions_create' => '/System Administrator/',
													'type' => '255'
												),
												'type' => array(
													'permissions_read' => '/System Administrator/',
													'permissions_update' => '/System Administrator/',
													'permissions_create' => '/System Administrator/',
													'type' => 'user_type'
												),
												'_permissions' => array(
													'create' => '/System Administrator/',
													'read' => '/System Administrator/',
													'update' => '/System Administrator/',
													'delete' => '/System Administrator/'
												),
												'_show' => 'user'
											);
										}
										;//echo "<h2>Interpretation</h2>";
										;//echo $config['_projectName']."<br>";
										$imageTables = array(); 				
										foreach ($config as $table_key => &$table) {
											if($table_key[0] == '_')
												continue;
											;//echo "&nbsp;&nbsp;&nbsp;&nbsp;".$table_key."<br>";
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
												;//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$column_key." | ".
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
											;//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_permissions create: ".$table['_permissions']['create']."<br>";
											;//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_permissions read: ".$table['_permissions']['read']."<br>";
											;//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_permissions update: ".$table['_permissions']['update']."<br>";
											;//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_permissions delete: ".$table['_permissions']['delete']."<br>";
											;//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;show: ".$table['_show']."<br>";
									   	}

									;//echo "</div>";
									;//echo '<div class="col-md-12">';
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

										;//echo "<h2>SQL</h2>";
										;//echo "<pre>".$sql."</pre>";
										$db_file_location = 'projects/'.$config['_projectName']."/".$config['_projectName'].".sql";
										;//echo "<a href='".$db_file_location."'>".$db_file_location."</a>";
										
										// Run _pre script
										;//echo "<h2>Build</h2>";
										exec('./build_pre.sh '.$config['_projectName'].' '.$db_host.' '.$db_user.' "'.$db_pass.'" '.implode(',', $imageTables));

										// Write files
										file_put_contents('projects/'.$config['_projectName'].'/admin/config.inc.php', '<?php $config=unserialize(\''.serialize($config).'\');?>');
										file_put_contents('projects/'.$config['_projectName'].'/'.$config['_projectName'].'.yml', $_POST['yaml']);
										file_put_contents('projects/'.$config['_projectName'].'/'.$config['_projectName'].'.sql', $sql);	

										// Run _post script
										;//echo "<pre>".
										exec('./build_post.sh '.$config['_projectName'].' '.$db_host.' '.$db_user.' "'.$db_pass.'" '.$db_port);//."</pre>";
									}

									require'config.inc.php';
								?>

		<?php require 'src/sidebar.inc.php'; ?>
		<div class="sidebarWrapper_page">
			<div class="footerDown_container">
				<div class="footerDown_body">
					<?php require 'src/menu.inc.php'; ?>
					<div class="container">
						<div class="row">
							<br><br>
							<h1>Framework 1.5</h1>
						</div>
						<div class="row" style="border: 1px solid black;">
							<div class="col-12" style="text-align: center;">
							<br><br><br><br><br><br>
							<br><br><br><br><br><br>
							<h4>Welcome!</h4>
							<br><br><br>
							<h4>This will be replaced by a welcome screen. I have to move all these things to the "Maker" section of the sidebar.</<h4></h4>
							<br><br><br><br><br><br>
							<br><br><br><br><br><br>	
							</div>							
						</div>
						<div class="row">
							<div class="col-md-4">
								<h2>YAML</h2>
								<form action="" method="POST" id="yamlForm" style="border: 1px solid black; padding: 5px;">
									<textarea name="json" rows="50" style="width: 100%"></textarea>
									<textarea name="yaml" rows="50" style="width: 100%" hidden></textarea>
									<br><br>
									<?php
										echo 'User: '.$db_user.'<br>'; 
										echo 'Port: '.$db_port.'<br>'; 
										echo 'Host: '.$db_host.'<br>'; 
									?>
									<br>
									<!-- TODO: Import with drag and drop -->
									<!-- TODO: Export should download a yaml file -->
									<button class="btn btn-default">Import</button>
									<button class="btn btn-default">Export</button>
									<button style="float: right;" type="submit" class="btn btn-primary">Submit</button>
								</form><br>
							</div>


							<div class="col-md-8">

							</div>
						</div>
					</div>	
				</div>
				<div class="footerDown_footer">
					FOOTER
				</div>
			</div>
		</div>
	</div>

				
	<?php require 'src/body_bottom.inc.php'; ?>
	<script src="vendor/yamljs/yaml.js"></script>
	<script>
		$.get('default.yml', function(data){
			$("textarea[name='json']").text(data);
		});
		$('#yamlForm').submit(function(e){
			$('textarea[name=yaml]').val( $('textarea[name=json]').val());
			$('textarea[name=json]').val( JSON.stringify(YAML.parse($('textarea[name=json]').val())));
		});

		$(document).ready(function() {
			$('.sidebar_trigger').on('click', function () {
				$('.sidebarWrapper_sidebar').toggleClass('active');
				$(this).toggleClass('active');
				if(!$('.sidebarWrapper_sidebar').hasClass('active'))
					$('.sidebarWrapper_sidebar ul .collapse').removeClass('show')
			});
		});
	</script>
</body>
</html>