<?php require 'session.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">

<?php require 'head.inc.php'; ?>

<body>
	<?php
		require 'menu.inc.php';
		echo $_SESSION['userName'].'<br>';
		echo $_SESSION['type'].'<br>';
	?>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="res" style="color:green;"></div>
			</div>
		</div>
	</div>
	<script>
		$.get('crud_read.php?table=user', function(data){
			$('#res').text(data);
		});
	</script>
	<?php
		include 'menu.inc.php';
	?>
</body>
</html>