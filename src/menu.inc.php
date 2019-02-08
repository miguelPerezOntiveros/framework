
			<nav class="navbar navbar-expand-lg navbar-dark topbar_topbar">
				<button type="button" onclick="$('.topbar_logout_link').attr('href', 'login.php?sidebar='+($('.sidebarWrapper_sidebar').hasClass('active')?'1':''));" class=<?php echo '"sidebar_trigger '.($_GET['sidebar']==1?'':'active').'"' ?>>
					<span></span>
					<span></span>
					<span></span>
				</button>
			    <span class="navbar_project"><?php echo $config['_show']?></span>
				<button class="navbar-toggler topbar_trigger" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span></span>
					<span></span>
					<span></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav ml-auto" style="white-space: nowrap;"> 
						<?php 
							$currentTable = isset($_GET['table'])? $_GET['table']: array_keys($config)[2];

							foreach ($config as $table => $value) {
								if($table[0] == '_' || $table == 'user' || $table == 'user_type' || $table == 'page' || $table == 'portlet' || $table == 'theme')
									continue;
								//error_log('permission read: /'.$value['_permissions']['read'].'/');
								//error_log('username: '.$_SESSION['type']);
								if($value['_permissions']['read'] != '-' && (!isset($_SESSION['userName']) ||
								 !preg_match('/'.$value['_permissions']['read'].'/', $_SESSION['type'])))
									continue;

								$tableNameToShow = $config[$table]['displayName'] ?: ucwords(str_replace("_"," ", $table ));
								echo '
								<li class="nav-item">
									<span onclick="loadSection(\''.$table.'\', \''.$tableNameToShow.'\');" class=\'tab nav-link\' data-table=\''.$table.'\' id=\'menu_'.$table.'\'>'.$tableNameToShow.'</span>
								</li>';
							}
						?>
							<li class=<?php echo '"nav-item dropdown '.(!$_SESSION['userName']?'d-none':'').'"' ?>>
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i></a>
								<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
									<span class="dropdown-header" style="text-align:right;">Welcome <?= $_SESSION['userName'] ?>!<br>(<?= $_SESSION['type']?>)</span>
									<a class="dropdown-item topbar_logout_link" href=<?php echo '"login.php?sidebar='.$_GET['sidebar'].'"' ?> style="text-align:right;">Log out <i class="fas fa-sign-out-alt"></i></a>
								</div>
							</li>
					</ul>
				</div>
			</nav>
