
		<nav class=<?php echo '"sidebarWrapper_sidebar '.($_GET['sidebar']==1?'':'active').'"' ?> >
			<div class="sidebar-header">
				<img src="/src/logo.png" height="100%" alt="" style="float:left; padding-right: 10px;">
				<span><h3>Maker Mike</h3></span>
			</div>
			<ul class="list-unstyled">
				<li class=<?php echo (dirname($_SERVER['REQUEST_URI']) == '/'?'active':'') ?>>
					<a href="/?sidebar=1">Home</a>
				</li>
				<li class=<?php echo (dirname($_SERVER['REQUEST_URI']) == '/projects/maker_mike/admin'?'active':'') ?>>
					<a href="/projects/maker_mike/admin/index.php?sidebar=1">Maker</a>
				</li>
				<li>
					<a href="#projectsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Projects</a>
					<ul class=<?php echo ($_GET['sidebar']==1?'"collapse list-unstyled show"':'"collapse list-unstyled"') ?> id="projectsSubmenu">
						<?php
							require_once 'db_connection.inc.php';
							$conn = new mysqli($db_host, $db_user, $db_pass, 'information_schema', $db_port);
							if ($conn->connect_errno)
								exit( json_encode((object) ['error' => 'Failed to connect to MySQL: ('.$conn->connect_errno.')'.$conn->connect_error]));
							$sql = "select SCHEMA_NAME from SCHEMATA where SCHEMA_NAME NOT IN('mysql', 'information_schema', 'performance_schema', 'sys', 'maker_mike');";
							if($result = $conn->query($sql)){
								while($row = $result->fetch_array(MYSQLI_NUM)){
									if($row[0] == $config['_projectName'])
										$projectView = true;
									echo '
										<li class='.($row[0] == $config['_projectName']? '"active"':'""').'>
											<a href="/projects/'.$row[0].'/admin/index.php?sidebar=1">'.$row[0].'</a>
										</li>';
								}
							}
						?>
					</ul>
				</li>
                <li>
                    <a href="https://github.com/miguelPerezOntiveros/framework">About</a>
                </li>

				<?php 
	                if($projectView)
	                	echo '
		                <li style="border-top: 1px solid #000032; color: #3bb6d4;">
							<p>'.$config['_projectName'].'</p>
		                </li>
						<li class="tab">
							<a href="#" onclick="loadSection(\'page\', \'Page\');">Page</a>
						</li>
						<li class="tab">
							<a href="#" onclick="loadSection(\'portlet\', \'Portlet\');">Portlet</a>
						</li>
						<li class="tab" id="menu_user_type">
							<a href="#" onclick="loadSection(\'user_type\', \'User Type\');">User Type</a>
						</li>
						<li class="tab" id="menu_user">
							<a href="#" onclick="loadSection(\'user\', \'User\');">User</a>
						</li>';
					if($config['_projectName'] == 'maker_mike' && dirname($_SERVER['REQUEST_URI']) == '/projects/maker_mike/admin')
						echo '
						<li style="border-top: 1px solid #000032; color: #3bb6d4;">
							<p>'.$config['_projectName'].'</p>
		                </li>
		                <li class="tab">
							<a href="#" onclick="loadSection(\'page\', \'Page\');">Page</a>
						</li>
						<li class="tab">
							<a href="#" onclick="loadSection(\'portlet\', \'Portlet\');">Portlet</a>
						</li>';
				?>
            </ul>
		</nav>
