<?php require 'session.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">

<?php require 'head.inc.php'; ?>

<body>
	<div id="footerDownContainer">
		<div id="footerDownBody">
			<?php require 'menu.inc.php'; ?>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<br>
						<div class="col-lg-12 bs-callout-left">
							<h2 id="title"></h2>
						</div>		
						<div class="col-lg-12">
							<br>
							<button class="btn btn-primary" onclick="$('#form').toggle();">&nbsp;Add&nbsp;<span class="glyphicon glyphicon-plus"></span>&nbsp;</button>
							<br><br>
							<table id="table" class="display" width="100%" cellspacing="0"></table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="footerDownFooter">
			<?php require 'foot.inc.php'; ?>
		</div>
	</div>
</body>

<script>
	var form;
	renderTable = function(name, displayName) { //TODO: esconder columna de id pero dejar el valor como data-id en el row
		$.get('crud_read.php?table=' + name, function(data){
			console.log(data);
			data = JSON.parse(data);

			if( $.fn.DataTable.isDataTable( '#table' ) ){
				 $('#table').DataTable().destroy();
			}
			
			$('#table').html('<thead><tr></tr></thead><tfoot><tr></tr></tfoot>');
		
			var columns = [];
			$.each(data.columns, function(i, e){
				$('tr').append('<th>'+e[0]+'</th>');
				columns.push({});
			});
			columns.push({
						defaultContent: '<a href="">Edit</a> <a href="">Delete</a>'
					});
			$('tr').append('<th></th>');

			// DataTable
			$('#table').DataTable({
				"data": data.data,
				"columns": columns
			});

			$('tbody').prepend('<div id="form"></div>');

			form = '';
			$.each(data.columns, function(i, e) {
				if(e[1] == 'int' || e[1] == 'double' || e[1] == 'float')
					form += '<input type="text"/><br>';
				else if(!isNaN(e[1]))	
					if(e[1] > 100)
						form += '<textarea></textarea><br>'; 
					else
						form += '<input type="text"/><br>';
				else if(e[1] == '\\*')
					form += '<div style="background: #f0f0f0"><br>drop file here<br></div><br>';
				else if(e[1] == 'date')
					form += '<input type="date"/><br>';
				else
					form += '<select><option>TODO: References. I still need to fill those in</option></select><br>';
			});
			form += '<input type="submit" value ="Add">';
			$('#form').html(form);
			$('#form').hide();
		});
		$('.tab').removeClass('active');
		$('#menu_'+name).addClass('active');
		$('#title').text(displayName);
	}
	$(document).ready(function() {
		$('.tab:first').click();
	});
</script>
</html>
	
<!-- 			<br><br><br><br><br><br><br><br><br><br><br><br>
			<form action='crud_create.php?table=gallery' method="POST" enctype="multipart/form-data">
				title: <input type="text" name="title"/><br>
				image: <input type="file" name="image"/><br>
				<input type="submit" value="Create"/><br><br><hr><br><br>
			</form>
			<form action='crud_update.php?table=gallery' method="POST" enctype="multipart/form-data">
				Id: <input type="text" name="id"/><br>
				title: <input type="text" name="title"/><br>
				image: <input type="file" name="image"/><br>
				<input type="submit" value="Update"/><br><br><hr><br><br>
			</form>
			<form action='crud_delete.php?table=gallery' method="POST">
				Id: <input type="text" name="id"/><br>
				<input type="submit" value="Delete"/><br><br><hr><br><br>
			</form>  
-->
