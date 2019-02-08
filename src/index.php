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
								<button type="button" onclick="handleCreate();" class="btn btn-primary form_plus_button"><i class="fas fa-plus form_plus"></i></button>
								<form id="cu_form" class="form_element"></form>
							</div>

							<div class="col-12 table_parent">
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
	<script src="/src/js/common.js"></script>
	<script src="/src/js/script.js"></script>
	<script>
		window._projectName = <?= "'".$config['_projectName']."'" ?>;
		window._show = <?= "'".$config['_show']."'" ?>;
	</script>
	<?php 
		$ext1 = 'ext/'.$config['_projectName'].'.ext.js';
		$ext2 = '../../../src/js/'.$config['_projectName'].'.ext.js';

		if(file_exists($ext1))
			echo '<script src='.$ext1.'></script>';
		else if(file_exists($ext2))
			echo '<script src='.$ext2.'></script>';
	?>
</body>
</html>