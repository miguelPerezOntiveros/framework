<div class="menu">
	<?php 
		$currentTable = isset($_GET['table'])? $_GET['table']:  key($config);
		// Iterate through tables
		foreach ($config as $table => $value) {
			if($table[0] == '_')
				continue;
			$tableNameToShow = (isset($config[$table]['displayName'])? 
							$config[$table]['displayName']: 
							ucwords(str_replace("_"," ", $table )));
			echo '<span onclick="loadSection(\''.$table.'\', \''.$tableNameToShow.'\');" class=\'tab\' id=\'menu_'.$table.'\'>'.$tableNameToShow.'</span>';
		}

	?>
	<div style="float: right;">
		Welcome <b><?= $_SESSION['userName'] ?></b>! Privileges: <b><?= $_SESSION['type'] ?></b>
		<a href="login.php" style="text-decoration: none;"><span class="tab">Log out</span></a>
	</div>
</div>