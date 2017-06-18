<div class="menu">
	<?php 
		$currentTable = isset($_GET['table'])? $_GET['table']:  key($config['tables']);
		// Iterate through tables
		$toTraverse = $config['tables'];
		reset($toTraverse);
		while ($table = current($toTraverse)) {
		        echo '<span class=\'tab\'>'.key($toTraverse).'</span>';
		       next($toTraverse);
		}

	?>
		<div style="float: right;">
			Welcome <b><?= $_SESSION['userName'] ?></b>! Privileges: <?= $_SESSION['type'] ?>. <a href="login.php">log out</a>
		</div>
	<hr>
</div>
