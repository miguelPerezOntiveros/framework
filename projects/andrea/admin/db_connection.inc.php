<?php
	$conn = new mysqli('127.0.0.1', 'root', 'TreBola13', 'andrea');
	if ($conn->connect_errno)
		exit('Failed to connect to MySQL: ('.$conn->connect_errno.')'.$conn->connect_error);
?>