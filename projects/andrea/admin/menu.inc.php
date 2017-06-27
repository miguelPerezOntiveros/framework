<div class="menu">
	<div class="pull-left">
		<?php 
			$currentTable = isset($_GET['table'])? $_GET['table']:  key($config['tables']);
			// Iterate through tables
			$toTraverse = $config['tables'];
			reset($toTraverse);
			while ($table = current($toTraverse)) {
			        echo '<strong><span class=\'tab padding20\' onclick=\'accessActive(event)\'>'.key($toTraverse).'</span></strong>';
			       next($toTraverse);
			}

		?>
	</div>
	<div class="pull-right">
		<div class="padding20">
			Welcome <b><?= $_SESSION['userName'] ?></b>! Privileges: <?= $_SESSION['type'] ?>. <a href="login.php">log out</a>
		</div>
	</div>
</div>

<script type="text/javascript">
	function accessActive(e){
		if(! $(e.target).hasClass('active')) $('.menu .tab.active').removeClass('active');
		$(e.target).addClass('active');
	}
</script>
