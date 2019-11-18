<!DOCTYPE html>
<html lang="en">
<?php require 'head.inc.php'; ?>
<body>
	<div class="sidebarWrapper_wrapper">
		<?php require 'sidebar.inc.php'; ?>
		<div class="sidebarWrapper_page">
			<div class="footerDown_container">
				<div class="footerDown_body">
					<?php require 'menu.inc.php'; ?>
					<div class="container">
						<div class="row">
							<h2><br>Welcome to <b>Maker Mike 1.0.2</b>!</h2>
						</div>
						<br>
						<br>
						<div class="row">
							<div class="col-4 offset-1" style="text-align: center;">
								<br>
								<img src="/src/logo.png" width="100%" alt="">
							</div>
							<div class="col-6 offset-1">
								<ul>
									<li>Single pane management of <b>all your projects</b><br></li>
									<li>Supports <b>pages</b>, <b>portlets</b> and <b>themes</b><br></li>
									<li>Intuitive <b>JS client library</b><br></li>
								</ul>
							</div>
						</div>
						<br>
						<br>
						<div class="row">
							<div class="col-12">
								<h5>Creating a Project</h5>
								<p>To create a project click on the <b>Maker</b> tab in the sidebar. Log in. Click on the <b>+</b> icon to fill in the required fields. You will see default config prepolulated that you can edit for quick project creation.</p>
								<p>Maker Mike prpject config is written in YAML. You can specify a column type to reference another table. You can specify a column to store blobs. You can specify a column to store multiple values. You can specify a column to store your project's table names.</p>

								<h5>Adding users</h5>
								<p>Each Maker Mike project has it's own set of users types and users. New projects get the default "System Administrator" and "User" user types and the "admin" and "user" users. Navigate to your project and click on the "User Type" or "User" tabs in the sidebar to manage them.</p>
								
								<h5>Portlets, Pages and Theems</h5>
								<p>Portlets can be used to create widget-like UIs, they query the CMS for data and produce HTML from it. Creating this HTML on the backend is necessary for web crawlers to be able to index your content without needing to run JavaScript.</p>
								<p>Pages can contain arbitraty HTML and can include in your Portlets as well.</p>
								<p>Themes are folders you upload to a project.</p>
							</div>							
						</div>
					</div>	
				</div>
				<div class="footerDown_footer">
					<?php require 'foot.inc.php'; ?>
				</div>
			</div>
		</div>
	</div>
	<?php require 'body_bottom.inc.php'; ?>
	<script src="/src/js/common.js"></script>
	<script>
		$(document).ready(function() {
			$('.sidebar_trigger').on('click', function () {
				$('.sidebarWrapper_sidebar').toggleClass('active');
				$(this).toggleClass('active');
				if(!$('.sidebarWrapper_sidebar').hasClass('active'))
					$('.sidebarWrapper_sidebar ul .collapse').removeClass('show')
			});
			doSidebarProjects();
		});
	</script>
</body>
</html>