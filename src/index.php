<?php require 'session.inc.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php require 'head.inc.php'; ?>
<body>
	<div class="sidebarWrapper_wrapper">
		<nav class="sidebarWrapper_sidebar active">
			<div class="sidebar-header">
				<h3>My CMS</h3>
			</div>
			<ul class="list-unstyled">
				<li>
					<a href="#">Contact</a>
				</li>
				<li class="active">
					<a href="#homeSubmenu" data-toggle="collapse" class="dropdown-toggle">Home</a>
					<ul class="collapse list-unstyled" id="homeSubmenu">
						<li>
							<a href="#">Home 1</a>
						</li>
						<li>
							<a href="#">Home 2</a>
						</li>
						<li>
							<a href="#">Home 3</a>
						</li>
					</ul>
				</li>
				<li>
					<a href="#">Contact</a>
				</li>
			</ul>
		</nav>
		<div class="sidebarWrapper_page">
			<div class="footerDown_container">
				<div class="footerDown_body">
					<?php require 'menu.inc.php'; ?>
					<div class="modal fade in" id="feedbackModal" tabindex="-1" role="dialog">
						<div class="modal-dialog modal_body" role="document">
						</div>
					</div>
					<div class="container">
						<div class="row">
							<div class="col-12 title">
								<h2 id="title"></h2>
							</div>

							<div class="col-12 form bs-callout-left">
								<br>
								<button type="button" onclick="handleCreate();" class="btn btn-primary"><i class="fas fa-plus"></i></button>
								<form class="form_element"></form>
							</div>

							<div class="col-12">
								<br><br>
								<table class="table_element display" width="100%" cellspacing="0"></table>
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
	<script src="js/script.js"></script>
</body>
</html>