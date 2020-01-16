<?php
require $_SERVER["DOCUMENT_ROOT"].'/src/set_config_and_params.inc.php';
require $_SERVER["DOCUMENT_ROOT"].'/src/session.inc.php'; ?>
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
					<div class="modal fade in" id="feedbackModal" tabindex="-1" role="dialog">
						<div class="modal-dialog modal_body" role="document">
						</div>
					</div>
					<div class="container" style="padding-left: 9px;">
						<div class="row">
							<div class="col-12 title">
								<h2 id="title"></h2>
							</div>

							<div class="col-12 form bs-callout-left">
								<br>
								<button type="button" onclick="handleCreate();" class="btn btn-primary form_plus_button"><i class="fas fa-plus form_plus"></i></button>
								<form id="cu_form" class="form_element"></form>
							</div>

							<div class="col-12 table_parent" style="padding-left: 30px;">
								<br><br>
								<table class="table_element display" width="100%" cellspacing="0"></table>
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
	<script src="/src/js/script.js"></script>
	<script>
		window._projectName = <?= "'".$config['_name']."'" ?>;
		window._show = <?= "'".$config['_show']."'" ?>;
	</script>
	<?php
		$ext1 = 'ext/'.$config['_name'].'.ext.js';
		$ext2 = '../../../src/js/'.$config['_name'].'.ext.js';

		if(file_exists($ext1))
			echo '<script src='.$ext1.'></script>';
		else if(file_exists($ext2))
			echo '<script src='.$ext2.'></script>';
	?>
</body>
</html>