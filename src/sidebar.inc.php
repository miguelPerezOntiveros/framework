
		<nav class="sidebarWrapper_sidebar active">
			<div class="sidebar-header">
				<img src="logo.png" height="100%" alt="" style="float:left; padding-right: 10px;">
				<span><h3>Maker Mike</h3></span>
			</div>
			<ul class="list-unstyled">
				<li>
					<a href="/">Maker</a>
				</li>
				<li class="active">
					<a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Projects</a>
					<ul class="collapse list-unstyled" id="homeSubmenu">
						<?php
							require 'db_connection.inc.php';
							$conn = new mysqli($db_host, $db_user, $db_pass, 'information_schema', $db_port);
							if ($conn->connect_errno)
								exit( json_encode((object) ['error' => 'Failed to connect to MySQL: ('.$conn->connect_errno.')'.$conn->connect_error]));
							$sql = "select SCHEMA_NAME from SCHEMATA where SCHEMA_NAME NOT IN('mysql', 'information_schema', 'performance_schema', 'sys');";
							if($result = $conn->query($sql)){
								while($row = $result->fetch_array(MYSQLI_NUM))
									echo '
										<li class='.($row[0] == $config['_projectName']? '"active"':'""').'>
											<a href="/projects/'.$row[0].'/admin/index.php">'.$row[0].'</a>
										</li>';
							}
						?>
					</ul>
				</li>			
                <li>
                    <a href="#">About</a>
                </li>
            </ul>
		</nav>
