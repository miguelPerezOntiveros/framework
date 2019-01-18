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
								<button type="button" onclick="handleCreate();" class="btn btn-primary"><i class="fas fa-plus form_plus"></i></button>
								<form id="cu_form" class="form_element"></form>
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
	<script>window._projectName = <?= "'".$config['_projectName']."'" ?></script>
	<script src="/src/js/script.js"></script>
</body>
</html>