<?php require 'session.inc.php'; ?>

<!DOCTYPE html>
<html lang="en">

<?php require 'head.inc.php'; ?>

<body>
	<div id="footerDownContainer">
		<div id="footerDownBody">
			<?php require 'menu.inc.php'; ?>
			<div class="modal fade in" id="feedbackModal" tabindex="-1" role="dialog">
				<div class="modal-dialog" id='feedbackModalBody' role="document">
				</div>
			</div>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="col-lg-12 can_have_callout bs-callout-left">
							<h2 id="title"></h2>
						</div>

						<div class="col-lg-12 newInquiry" id='callout' class="can_have_callout bs-callout-left">
							<br>
							<button type="button" id="createBtn" onclick="handleCreate();" class="btn btn-primary"><span class="glyphicon glyphicon-plus"></button>
							<form id="cu_form"></form>
						</div>

						<div class="col-lg-12">
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
	handleCreate = function(){
		window.crud_mode = 'create';
		toggleForm();
		$('input[type=file]').each(function(i, e){
			e.required = true;
		});
	}
	handleEdit = function(e){
		window.crud_mode = 'update';
		toggleForm();
		$('input[type=file]').each(function(i, e){
			e.required = false;
		});
		var values = [];
		$(e).parent().parent().parent().find('td').slice(0, -1).each(function(i, e) {
			values.push($(e).html());
		})
		console.log(values);
		$('#cu_form').find('textarea, select, input[type!="submit"]').each(function(i, e){
			if($(e).is('select'))
				$(e).val(values[i].split('-')[0])
			else if ($(e).attr('type') == 'file')
				; // don't fill in value for files
			else
				$(e).val(values[i]);
		})
	}
	handleDelete = function(e){
		window.crud_mode = 'delete';
		var id = $($(e).parent().parent().parent().find('td')[0]).text();
		$('#cu_form input[name=id]').val(id);
		$('#cu_form').trigger('submit');
	}
	toggleForm = function (){
		$('#cu_form')[0].reset();
		$('.catcherFilesLabel').text('');
		$('#cu_form').toggle('slow');
		if ($('#callout').hasClass('bs-callout-left'))
			$('#callout').removeClass('bs-callout-left');
		else
			$('#callout').addClass('bs-callout-left');
	}

	loadSection = function(name, displayName){
		$('#callout').removeClass('bs-callout-left');
		doTable(name, displayName, true);
		doMenu(name, displayName);
	}

	doMenu = function(name, displayName){
		$('.tab').removeClass('active');
		$('#menu_'+name).addClass('active');
		$('#title').text(displayName);
	}

	doForm = function(columns){
		var form = '';
		$.each(columns, function(i, e) {
			if(i==0) // The id row will be hidden to the user
				form += '<input type="hidden" name ="'+e[0]+'"/><br>';
			else{
				form += '<b>'+e[0]+':</b></br>'
				if(e[1] == 'int' || e[1] == 'double' || e[1] == 'float')
					form += '<input type="text" name ="'+e[0]+'"/><br>';
				else if(!isNaN(e[1]))	
						form += '<textarea  name="'+e[0]+'" form="cu_form" required></textarea><br>'; 
				else if(e[1] == '\\*')
					form += '<input type="file" name="'+e[0]+'" id="file_'+e[0]+'" required> <div class="catcher" data-input="file_'+e[0]+'" ondragover="return false"><span class="glyphicon glyphicon-arrow-down" style="font-size: 3em;"></span><br>(Current file will persist if no new file is chosen)<span class="catcherFilesLabel">Drop file here</span></div><br>';
				else if(e[1] == 'date')
					form += '<input name="'+e[0]+'" type="date" required><br>';
				else if(e[1] == 'boolean')
					form += ' <select name="'+e[0]+'"><option value="0">0-No</option><option value="1">1-Yes</option></select><br>';
				else{
					form += '<select name="'+e[0]+'"></select><br>';
					$.get('crud_read.php?table=' + e[1] + '&show=true', function(response){
						response = JSON.parse(response);
						$.each(response.data, function(i, el){
							$('select[name="'+e[0]+'"]').append('<option value="'+el[0]+'">'+el[1]+'</option>');
						});
					});
				}
			}
		});

		form += '<input type="submit" class="btn btn-primary" style="float:right" value="OK">';
		$('#cu_form').html(form);
		$('#cu_form .catcher').each(function(i, el){
			el.addEventListener('drop', function(ev){
				ev.stopPropagation();
				ev.preventDefault();
				var file = ev.dataTransfer.files[0];
				var name = file.name;

				$(el).find('.catcherFilesLabel').text(name);
				$('#'+$(el).data('input'))[0].files = ev.dataTransfer.files;
	//			$label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace( '{count}', files.length ) : files[ 0 ].name);
   
			}, false);
		});
		$('#cu_form').hide();
	}

	doTable = function(name, displayName, thenDoForm) {
		window.name = name = name || window.name; 
		window.displayName = displayName = displayName || window.displayName; 

		$.get('crud_read.php?table=' + name, function(data){
			console.log(data);
			data = JSON.parse(data);
			if(data.error){
				$('#feedbackModalBody').html('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only"></span>&nbsp;Error: '+data.error+'</div>');
				$("#feedbackModal").modal("show");
				return;
			}

			if( $.fn.DataTable.isDataTable('#table')){
				 $('#table').DataTable().destroy();
			}
			
			$('#table').html('<thead><tr></tr></thead><tfoot><tr></tr></tfoot>');
		
			var columns = [];
			$.each(data.columns, function(i, e){
				$('tr').append('<th>'+e[0]+'</th>');
				if(e[1] == '\\*')
					columns.push({ "render": function (data, type, full, meta) {return "<a href='uploads/"+window.name+'/'+data+"'><img style='width:60px;' src='uploads/"+window.name+'/'+data+"'/></a>"; } });
				else
					columns.push({});
			});
			columns.push({
				defaultContent: '<center><button onclick="handleEdit(this)" class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-pencil"></span></button>&nbsp;&nbsp;&nbsp;&nbsp;<button onclick="handleDelete(this)" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span></button></center>'
			});
			$('tr').append('<th></th>');

			// DataTable
			$('#table').DataTable({
				"data": data.data,
				"columns": columns
			});

			if(thenDoForm)
				doForm(data.columns);
		});
	}
	$(document).ready(function() {
		var endpoint = {"create": "crud_create.php", "update": "crud_update.php", "delete":"crud_delete.php"};
		$('#cu_form').submit(function(e){
			e.preventDefault();
			var formData = new FormData($('#cu_form')[0]);
			$.ajax({
				type: "POST",
				url: endpoint[window.crud_mode] + "?table=" + window.name,
				data: formData,
				success: function(data) {
					response = JSON.parse(data);
					$("#feedbackModal").modal("show");
					
					if(response.error)
						if(response.error == 'login')
							window.location = 'login.php';
						else
							$('#feedbackModalBody').html('<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only"></span>&nbsp;Error: '+response.error+'</div>');
					else{
						$('#feedbackModalBody').html('<div class="alert alert-success" role="alert"><span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span><span class="sr-only"></span>&nbsp;'+response.success+'</div>');								
						setTimeout(function(){
							$("#feedbackModal").modal("hide");
						}, 1000);
					}
					
					doTable(undefined, undefined, false);
					if(window.crud_mode != 'delete')
						toggleForm();
				},
				enctype: "multipart/form-data",
				contentType: "multipart/mixed; boundary=frontier",
				contentType: false,
				processData: false
			});
		});

		$('.tab:first').click();
	});
</script>
</html>




<!-- 	
			<br><br><br><br><br><br><br><br><br><br><br><br>
			<form action='crud_create.php?table=gallery' method="POST" id="myCreate" enctype="multipart/form-data">
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