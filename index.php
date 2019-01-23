<?php 
	require 'config.inc.php';
	require 'src/session.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<?php require 'src/head.inc.php'; ?>
<body>
	<div class="sidebarWrapper_wrapper">
		<?php
			require 'engine.php';
			require 'config.inc.php';
			require 'src/sidebar.inc.php'; 
		?>
		<div class="sidebarWrapper_page">
			<div class="footerDown_container">
				<div class="footerDown_body">
					<?php require 'src/menu.inc.php'; ?>
					<div class="container">
						<div class="row">
							<br><br>
							<h1>Framework 1.5</h1>
						</div>
						<div class="row" style="border: 1px solid black;">
							<div class="col-12" style="text-align: center;">
							<br><br><br><br><br><br>
							<br><br><br><br><br><br>
							<h4>Welcome!</h4>
							<br><br><br>
							<h4>This will be replaced by a welcome screen. I have to move all these things to the "Maker" section of the sidebar.</<h4></h4>
							<br><br><br><br><br><br>
							<br><br><br><br><br><br>	
							</div>							
						</div>
						<div class="row">
							<div class="col-md-4">
								<h2>YAML</h2>
								<form action="" method="POST" id="yamlForm" style="border: 1px solid black; padding: 5px;">
									<textarea name="config" rows="50" style="width: 100%"></textarea>
									<textarea name="yaml" rows="50" style="width: 100%" hidden></textarea>
									
									<button style="float: right;" type="submit" class="btn btn-primary">Submit</button>
								</form><br>
							</div>
						</div>
					</div>	
				</div>
				<div class="footerDown_footer">
					<?php require 'src/foot.inc.php'; ?>
				</div>
			</div>
		</div>
	</div>

				
	<?php require 'src/body_bottom.inc.php'; ?>
	<script src="vendor/yamljs/yaml.js"></script>
	<script>
		$.get('default.yml', function(data){
			$("textarea[name='config']").text(data);
		});
		$('#yamlForm').submit(function(e){
			$('textarea[name=yaml]').val( $('textarea[name=config]').val());
			$('textarea[name=config]').val( JSON.stringify(YAML.parse($('textarea[name=config]').val())));
		});

		$(document).ready(function() {
			$('.sidebar_trigger').on('click', function () {
				$('.sidebarWrapper_sidebar').toggleClass('active');
				$(this).toggleClass('active');
				if(!$('.sidebarWrapper_sidebar').hasClass('active'))
					$('.sidebarWrapper_sidebar ul .collapse').removeClass('show')
			});
		});
	</script>
</body>
</html>