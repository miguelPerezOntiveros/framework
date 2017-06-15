<?php
	require 'session.inc.php';
	require 'config.inc.php';
	require 'db_connection.inc.php';
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
				proj Name: 
				<?php
					echo $config['projectName'].'<br>';
					echo $config['tables']['user']['columns']['type']['permisions'];
				?>
			</div>
		</div>
	</div>
	<script>
		;
	</script>
	<?php
		include 'menu.inc.php';
	?>
</body>
</html>