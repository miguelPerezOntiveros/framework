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

				<form action=<?= '"crud_create.php?table='.$currentTable.'"' ?> method="POST" enctype="multipart/form-data">
					title: <input type="text" name="title"/><br>
					image: <input type="file" name="image"/><br>
					<input type="submit" value="Create"/><br><br><hr><br><br>
				</form>
				<form action=<?= '"crud_update.php?table='.$currentTable.'"' ?> method="POST" enctype="multipart/form-data">
					Id: <input type="text" name="id"/><br>
					title: <input type="text" name="title"/><br>
					image: <input type="file" name="image"/><br>
					<input type="submit" value="Update"/><br><br><hr><br><br>
				</form>
				<form action=<?= '"crud_delete.php?table='.$currentTable.'"' ?> method="POST">
					Id: <input type="text" name="id"/><br>
					<input type="submit" value="Delete"/><br><br><hr><br><br>
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