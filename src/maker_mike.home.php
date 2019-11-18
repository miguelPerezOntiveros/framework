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
									<li>Manage <b>pages</b>, <b>portlets</b> and <b>themes</b><br></li>
									<li>Intuitive <b>JS client library</b><br></li>
								</ul>
							</div>
						</div>
						<br>
						<br>
						<div class="row">
							<div class="col-12">
								<h5>Creating a Project</h5>
								<p>To create a project..</p>

								<h5>Adding users</h5>
								<p>Maker Mike supports...</p>

								<h5>File support</h5>
								<p>Maker Mike uses...</p>
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