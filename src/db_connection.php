<?php
	$proy = $config['projectName'];
	mysql_connect('host', 'user', 'pass') or die(mysql_error());
	mysql_select_db($proy) or die(mysql_error());
?>