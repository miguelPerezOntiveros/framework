<?php require 'session.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">

<?php require 'head.inc.php'; ?>

<body>
	<?php require 'menu.inc.php'; ?>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<table id="table" class="display" width="100%" cellspacing="0"></table>

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
		<?php 
			echo 'var currentTable = "'.$currentTable.'";';
		?>
		renderTable = function(tableName) {
			$.get('crud_read.php?table=' + tableName, function(data){
				console.log(data);
				data = JSON.parse(data);


				if( $.fn.DataTable.isDataTable( '#table' ) ){
					 $('#table').DataTable().destroy();
				}
				
				$('#table').html('<thead><tr></tr></thead><tfoot><tr></tr></tfoot>');
			
				$.each(data.columns, function(i, e){
					$('tr').append('<th>'+e+'</th>');
				});

				$('#table').DataTable({
					"data": data.data,
				});
			})
		}
		$(document).ready(function() {
			renderTable(currentTable);
		});
	</script>
	<?php
		//TODO: Validar largo máximo en inputs para varchars

	include 'foot.inc.php';
	?>
</body>
</html>