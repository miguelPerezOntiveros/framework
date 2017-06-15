<?php
	require 'session.inc.php';
	isset($_GET['table']) || die('No requested table');
	require 'config.inc.php';
	//TODO: Check permission to table
	require 'db_connection.inc.php';

	$allowedColumns = [];
	//TODO: fill in allowed columns from $config
	echo $config['projectName'].'<br>';
	echo 'users table permissions: '.$config['tables']['user']['permisions'];
	
	$sql = 'SELECT * from '.$_GET['table'];
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	    	//TODO: traverse the whole row freely as only allowed columns were asked for
	    	var_dump($row);
	        echo "<br> row1: " . $row[1]. " row2: " . $row[2]."<br>";
	    }
	} else {
	    echo "0 results";
	}
	$conn->close();
	
	//TODO: generate the JSON output. use a lib?
?>
