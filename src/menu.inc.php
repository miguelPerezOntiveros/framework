<div class="menu">
	<?php 
		$currentTable = isset($_GET['table'])? $_GET['table']:  key($config['tables']);
		// Iterate through tables
		$toTraverse = $config['tables'];
		reset($toTraverse);
		while ($table = current($toTraverse)) {
			$tableNameToShow = (isset($config['tables'][key($toTraverse)]['displayName'])? 
							$config['tables'][key($toTraverse)]['displayName']: 
							ucwords(str_replace("_"," ", key($toTraverse) )));
			echo '<span onclick="loadSection(\''.key($toTraverse).'\', \''.$tableNameToShow.'\');" class=\'tab\' id=\'menu_'.key($toTraverse).'\'>'.$tableNameToShow.'</span>';
			next($toTraverse);
		}

	?>
	<div style="float: right;">
		Welcome <b><?= $_SESSION['userName'] ?></b>! Privileges: <b><?= $_SESSION['type'] ?></b>
		<a href="login.php" style="text-decoration: none;"><span class="tab">Log out</span></a>
	</div>
</div>