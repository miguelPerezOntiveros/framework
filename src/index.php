<?php
	require 'session.inc.php';
	require 'config.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	
	<!-- jQuery -->
	<script src="vendor/jquery-2.2.4/jquery-2.2.4.min.js"></script>

	<!-- Bootstrap -->
	<script src="vendor/bootstrap-3.3.6/bootstrap.min.js"></script>
	<link href="vendor/bootstrap-3.3.6/bootstrap.min.css" rel="stylesheet">

	<!-- datatables -->
	<script src="vendor/datatables-1.10.15/jquery.dataTables.min.js"></script>
	<link href="vendor/datatables-1.10.15/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>
	<?php
		//require 'menu.php';
	?>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="res"></div>
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