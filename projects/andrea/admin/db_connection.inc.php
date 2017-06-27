<?php
	$conn = new mysqli('127.0.0.1', 'root', 'x1X48bc0Wsm1.sp25', 'andrea');
	if ($conn->connect_errno)
		exit('Failed to connect to MySQL: ('.$conn->connect_errno.')'.$conn->connect_error);
?>