<?php 	require_once 'config.inc.php';
require 'session.inc.php'; ?>
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
							<h2><br>Welcome to <b>Maker Mike 1.5</b>!</h2>
						</div>
						<br><br><br>
						<div class="row">
							<div class="col-12" style="text-align: center;">
							<br><br><br><br>
							<img src="/src/logo.png" alt="">
							<br><br><br><br>
							<br><br><br>
							<h4>In version 1.5 of Maker Mike, you can manage all your projects from one same place, as well as create pages, portlets and themes for them.</<h4></h4>
							<br><br><br><br><br><br>
							<br><br><br><br><br><br>	
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