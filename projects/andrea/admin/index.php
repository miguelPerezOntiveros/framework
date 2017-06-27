<?php require 'session.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">

<?php require 'head.inc.php'; ?>

<body>
	<div class="container">
		<?php require 'menu.inc.php'; ?>
		<div class="container">
			<div id="res" style="color:green;"></div>
			<div class="pull-right" onclick="openForm()">Add</div>
			<div class="row">
				<div class="col-md-12">
					<div class="table-responsive">
						<table id="example" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" width="100%" role="grid" aria-describedby="example_info" style="width: 100%;">
							<thead>
								<tr>
									<th>head</th>
									<th>head</th>
									<th>head</th>
									<th>head</th>
									<th>Modify</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>--</td>
									<td>--</td>
									<td>--</td>
									<td>--</td>
									<td>Ed El</td>
								</tr>
							</tbody>
						</table>
					</div>

					<form action=<?= '"crud_create.php?table='.$currentTable.'"' ?> method="POST">
						Name: <input type="text" name="name"/>
						<input type="submit" value="Create test"/>
					</form>
					<form action=<?= '"crud_delete.php?table='.$currentTable.'"' ?> method="POST">
						Id: <input type="text" name="id"/>
						<input type="submit" value="Delete test"/>
					</form>
					<form action=<?= '"crud_update.php?table='.$currentTable.'"' ?> method="POST">
						Id: <input type="text" name="id"/>
						Name: <input type="text" name="name"/>
						<input type="submit" value="Update test"/>
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
	</div>
	<script>
		$('#example').dataTable();
		$('#example').removeClass( 'display' )
		.addClass('table table-striped table-bordered');
		function openForm(){
			console.log('open');
		}
	</script>
</body>
</html>