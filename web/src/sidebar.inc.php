
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
					if(isset($config['_name'])){
		                echo '
			                <li style="border-top: 1px solid #000032; color: #3bb6d4;">
								<p>'.$config['_show'].' ('.$config['_name'].')</p>
			                </li><ul class=\'list-unstyled sidebar_project_specific_ul\'>';
		                if($config['_name'] != 'maker_mike')	
			                echo '
							<li>
								<a id="menu_export" class="tab" data-table=\'export\' onclick="loadSection(\'export\', \'\', true);">Export</a>
							</li>
							<li>
								<a id="menu_import" class="tab" data-table=\'import\' onclick="loadSection(\'import\', \'\', true);">Import</a>
							</li>
							<li>
								<a id="menu_theme" class="tab" data-table=\'theme\' onclick="loadSection(\'theme\', \'\', true);">Theme</a>
							</li>
							<li>
								<a id="menu_page" class="tab" data-table=\'page\' onclick="loadSection(\'page\', \'\', true);">Page</a>
							</li>
							<li>
								<a id="menu_portlet" class="tab" data-table=\'portlet\' onclick="loadSection(\'portlet\', \'\', true);">Portlet</a>
							</li>
							<li>
								<a id="menu_image" class="tab" data-table=\'image\' onclick="loadSection(\'image\', \'\', true);">Image</a>
							</li>
							<li>
								<a id="menu_text" class="tab" data-table=\'text\' onclick="loadSection(\'text\', \'\', true);">Text</a>
							</li>';
						echo '
							<li>
								<a id="menu_user" class="tab" data-table=\'user\' onclick="loadSection(\'user\', \'\', true);">User</a>
							</li>
							<li>
								<a id="menu_user_type" class="tab" data-table=\'user_type\' onclick="loadSection(\'user_type\', \'\', true);">User Type</a>
							</li></ul>';
					}
				?>
            </ul>
		</nav>
