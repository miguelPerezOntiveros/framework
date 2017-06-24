<?php require 'session.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">

<?php require 'head.inc.php'; ?>

<body>
	<?php require 'menu.inc.php'; ?>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div id="res" style="color:green;"></div>

				<form action=<?= '"crud_create.php?table='.$currentTable.'"' ?> method="POST">
					Name: <input type="text" name="name"></input>
					<input type="submit">Create test</input>
				</form>
			</div>
		</div>
	</div>
	<script>
		$.get(<?= '"crud_read.php?table='.$currentTable.'"' ?>, function(data){
			$('#res').text(data);
		});
	</script>
	<?php
		//TODO: Validar largo máximo en inputs para varchars
						
		include 'foot.inc.php';
	?>
</body>
</html>