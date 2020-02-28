
			<nav class="navbar navbar-expand-lg navbar-dark topbar_topbar">
				<button type="button" class=<?php echo '"sidebar_trigger '.($_GET['sidebar']==1?'':'active').'"' ?>>
					<span></span>
					<span></span>
					<span></span>
				</button>
			    <span class="navbar_project"><?php 
			    		echo $config['_show']?
			    			$config['_show'].' ('.$config['_name'].')':
			    			'Home'; ?>
			    </span>
					<?php 
					$atLeastOneEntryAdded = false;
					$topNavLi = "";
					if(isset($config))
						foreach ($config as $table => $value) {
							if($table[0] == '_' || $table == 'export' || $table == 'import' || $table == 'user' || $table == 'user_type' || $table == 'page' || $table == 'portlet' || $table == 'theme' || $table == 'image' || $table == 'text')
								continue;
							//error_log('permission read: /'.$value['_permissions']['read'].'/');
							//error_log('username: '.$_SESSION['type']);
							if($value['_permissions']['read'] != '-' && (!isset($_SESSION['userName']) ||
							 !preg_match('/'.$value['_permissions']['read'].'/', $_SESSION['type'])))
								continue;

							$atLeastOneEntryAdded = true;

							$tableNameToShow = $config[$table]['displayName'] ?: ucwords(str_replace("_"," ", $table ));
							$topNavLi .= '
							<li class="nav-item">
								<span onclick="loadSection(\''.$table.'\', \''.$tableNameToShow.'\', true);" class=\'tab nav-link\' data-table=\''.$table.'\' id=\'menu_'.$table.'\'>'.$tableNameToShow.'</span>
							</li>';
						}
				?>
				<button class=<?php echo '"navbar-toggler topbar_trigger '.(!$atLeastOneEntryAdded && !$_SESSION['userName']?'d-none':'').'"' ?> type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span></span>
					<span></span>
					<span></span>
				</button>
				<div id="navbarSupportedContent" class="collapse navbar-collapse">
					<ul class="navbar-nav ml-auto" style="white-space: nowrap;"> 
						<?php 
							echo $topNavLi;
						?>
							<li class=<?php echo '"nav-item dropdown '.(!$_SESSION['userName']?'d-none':'').'"' ?>>
								<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i></a>
								<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
									<span class="dropdown-header" style="text-align:right;">Welcome <?= $_SESSION['userName'] ?>!<br>(<?= $_SESSION['type']?>)</span>
									<a class="dropdown-item topbar_logout_link" href="#" style="text-align:right;">Log out <i class="fas fa-sign-out-alt"></i></a>
								</div>
							</li>
					</ul>
				</div>
			</nav>
