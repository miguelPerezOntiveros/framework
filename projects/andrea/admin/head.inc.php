<?php 
	require_once 'config.inc.php';
	echo '<!--' . var_dump($config) . '-->';
?>

<head>
	<meta charset="UTF-8">
	<title><?= $config['projectName']?></title>

	<!-- jQuery -->
	<script src="vendor/jquery-2.2.4/jquery-2.2.4.min.js"></script>

	<!-- Bootstrap -->
	<script src="vendor/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
	<link href="vendor/bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- datatables -->
	<script type="text/javascript" src="vendor/datatables-1.10.15/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="vendor/datatables-1.10.15/dataTables.bootstrap.min.js"></script>
	<link rel="stylesheet" href="vendor/datatables-1.10.15/dataTables.bootstrap.min.css">

	<!-- Custom css -->
	<link href="css/style.css" rel="stylesheet">	
</head>