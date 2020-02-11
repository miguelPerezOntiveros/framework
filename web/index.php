<!DOCTYPE html>
<html lang="en">
<?php require $_SERVER["DOCUMENT_ROOT"].'/src/head.inc.php'; ?>
<body>
	<div class="sidebarWrapper_wrapper">
		<?php require $_SERVER["DOCUMENT_ROOT"].'/src/sidebar.inc.php'; ?>
		<div class="sidebarWrapper_page">
			<div class="footerDown_container">
				<div class="footerDown_body">
					<?php require $_SERVER["DOCUMENT_ROOT"].'/src/menu.inc.php'; ?>
					<div class="container">
						<div class="row">
							<h2><br>Introducing <b>Maker Mike 1.0.2</b></h2>
						</div>
						<br>
						<br>
						<div class="row">
							<div class="col-4 offset-1" style="text-align: center;">
								<br>
								<img src="/src/logo.png" width="100%" alt="">
								<a target="_blank" href="https://github.com/miguelPerezOntiveros/framework">View on Github</a>
							</div>
							<div class="col-6 offset-1">
								<ul>
									<li>Single pane management of <b>all your projects</b><br></li>
									<li>Supports <b>pages</b>, <b>portlets</b> and <b>themes</b><br></li>
									<li>Intuitive <b>JS client library</b><br></li>
									<li><b>Import</b> and <b>Export</b> your projects<br></li>
								</ul>
							</div>
						</div>
						<br>
						<br>
						<div class="row">
							<div class="col-12">
								<h5>Creating a Project</h5>
								<p>To create a project click on the <b>Maker</b> tab in the sidebar. Log in. Click on the <b>+</b> icon to fill in the required fields. You will see default config prepolulated that you can edit for quick project creation.</p>

								<h6>YAML Configuration</h6>
								<p>Maker Mike project config is written in YAML and contains table and column metadata. You can specify columns to reference values from other tables, store blobs, store multiple values, store table names, or just the primitive datatypes.</p>
								<p>This is also where individual permissions to create, read, update and delete are configured for every table and column.</p>

								<h5>Managing users</h5>
								<p>Each Maker Mike project has it's own set of users types and users. New projects get the default "System Administrator" and "User" user types and the "admin" and "user" users. Navigate to your project and click on the "User Type" or "User" tabs in the sidebar to manage them.</p>
								
								<h5>Portlets, Pages and Themes</h5>
								<p>Portlets can be used to create widget-like UIs, they query the CMS for data and produce HTML from it. Creating this HTML on the backend is necessary for web crawlers to be able to index your content without needing to run JavaScript.</p>
								<p>Pages will be stores as php's files and can contain arbitraty HTML and your Portlets.</p>
								<p>Themes are folders you upload to a project.</p>

								<h5>Project extentions</h5>
								<p>You can extend your projects' functionality on both the front-end and back-end. The back-end can be extended on any of the 4 crud services for any table, while the front-end provides various extentions points on useful events.</p>

								<h5>Export and import your projects</h5>
								<p>Exporting and importing a project enables you to promote functionality and data across environments, as well as to recover from back-ups. You can choose what you want included in an export file and when the time comes to import you can also choose what parts of an export file you want to import. Pages, Themes and Extentions as well as inidividual tables are all eligible to be exported and imported.</p>
							</div>							
						</div>
					</div>	
				</div>
				<div class="footerDown_footer">
					<?php require $_SERVER["DOCUMENT_ROOT"].'/src/foot.inc.php'; ?>
				</div>
			</div>
		</div>
	</div>
	<?php require $_SERVER["DOCUMENT_ROOT"].'/src/body_bottom.inc.php'; ?>
	<script src="/src/js/common.js"></script>
	<script>
		$(document).ready(function() {
			$('.sidebar_trigger').on('click', function () {
				$('.sidebarWrapper_sidebar').toggleClass('active');
				$(this).toggleClass('active');
				if(!$('.sidebarWrapper_sidebar').hasClass('active')){
					$('.sidebarWrapper_sidebar ul .collapse').removeClass('show')
					history.replaceState({}, "", "?sidebar=1");
				}
				else
					history.replaceState({}, "", "?");
			});
			doSidebarProjects();
		});
	</script>
</body>
</html>