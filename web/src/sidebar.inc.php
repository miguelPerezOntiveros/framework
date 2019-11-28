
		<nav class=<?php echo '"sidebarWrapper_sidebar '.($_GET['sidebar']==1?'':'active').'"' ?> >
			<a href="/index.php?sidebar=1">
				<div class="sidebar-header">
					<img src="/src/logo.png" height="100%" alt="" style="  display: block; margin-left: auto; margin-right: auto;">
				</div>
			</a>
			<ul class="list-unstyled">
				<li class=<?php echo (explode('?', $_SERVER['REQUEST_URI'], 2)[0] == '/projects/maker_mike/admin/home.php'?'active':'') ?>>
					<a href="/index.php?sidebar=1">Home</a>
				</li>
				<li class=<?php echo (explode('?', $_SERVER['REQUEST_URI'], 2)[0] == '/projects/maker_mike/admin/index.php'?'active':'') ?>>
					<a href="/projects/maker_mike/admin/index.php?sidebar=1">Maker</a>
				</li>
				<li>
					<a href="#projectsSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Projects</a>
					<ul class=<?php echo '"collapse list-unstyled sidebar_projects'.($_GET['sidebar']==1?' show"':'"') ?> id="projectsSubmenu"></ul>
				</li>
				<?php
					if(isset($config['_projectName'])){
		                echo '
			                <li style="border-top: 1px solid #000032; color: #3bb6d4;">
								<p>'.$config['_show'].' ('.$config['_projectName'].')</p>
			                </li>';
		                if($config['_projectName'] != 'maker_mike')	
			                echo '
							<li class="tab" id="menu_page">
								<a href="#" onclick="loadSection(\'page\', \'Page\');">Page</a>
							</li>
							<li class="tab" id="menu_portlet">
								<a href="#" onclick="loadSection(\'portlet\', \'Portlet\');">Portlet</a>
							</li>
							<li class="tab" id="menu_theme">
								<a href="#" onclick="loadSection(\'theme\', \'Theme\');">Theme</a>
							</li>';
						echo '
							<li class="tab" id="menu_user_type">
								<a href="#" onclick="loadSection(\'user_type\', \'User Type\');">User Type</a>
							</li>
							<li class="tab" id="menu_user">
								<a href="#" onclick="loadSection(\'user\', \'User\');">User</a>
							</li>';
					}
				?>
            </ul>
		</nav>
