handleCreate = function(){
	window.crud_mode = 'create';
	toggleForm();
	$('input[type=file]').each(function(i, e){
		e.required = true;
	});
}
handleEdit = function(e){
	window.crud_mode = 'update';
	toggleForm("open");
	$('input[type=file]').each(function(i, e){
		e.required = false;
	});
	var values = [];
	$(e).parent().parent().parent().find('td').slice(0, -1).each(function(i, e) {
		values.push($(e).text());
	})
	console.log(values);
	$('.form_element').find('textarea, select, input[type!="submit"]').each(function(i, e){
		if($(e).is('select[multiple]'))
			$(e).val(values[i].split('-').slice(1));
		else if ($(e).is('select'))
			$(e).val(values[i].split('-')[0]);
		else if ($(e).attr('type') == 'file')
			; // don't fill in value for files
		else
			$(e).val(values[i]);
	})
}
handleDelete = function(e){
	window.crud_mode = 'delete';
	var id = $($(e).parent().parent().parent().find('td')[0]).text();
	$('.form_element input[name=id]').val(id);
	$('.form_element').trigger('submit');
}
toggleForm = function (only){
	if(!only || 
		only == "close" && $('.form').hasClass('bs-callout-left') ||
		only == "open" && !$('.form').hasClass('bs-callout-left')){
		$('.form_element')[0].reset();
		$('.catcherFilesLabel').text('Drop file here');
		$('.form_element').toggle('slow');
		if ($('.form').toggleClass('bs-callout-left'))
		$('.form_plus').toggleClass('rotated');
	}
}

loadSection = function(name, displayName){
	$('.form').removeClass('bs-callout-left');
	$('.form_plus').removeClass('rotated');
	doTable(name, displayName, true);
	doMenu(name, displayName);
}

doMenu = function(name, displayName){
	$('.tab').removeClass('active');
	$('#menu_'+name).addClass('active');
	$('#title').text(displayName);
}

doForm = function(columns){
	$('.form_element').html('');
	$.each(columns, function(i, e) {
		var form = '';
		if(i==0) // The id row will be hidden to the user
			form += '<input type="hidden" name ="'+e[0]+'"/><br>';
		else{
			form += '<b>'+e[2]+':</b></br>'
			if(e[3] == 'multi'){
				$('.form_element').append(form+'<select name="'+e[0]+'[]" multiple required></select><br>');
				form = '';
				if(name == 'portlet' && e[0] == 'query_tables')
					$.each($('.navbar-nav li span').slice(0, -1), function(i, el){
						$('select[name="'+e[0]+'[]"]').append('<option value="'+$(el).data('table')+'">'+$(el).text()+'</option>');
					});
				else
					$.get('/src/crud_read.php?project='+window._projectName+'&table=' + e[1] + '&show=true', function(response){
						response = JSON.parse(response);
						$.each(response.data, function(i, el){
							$('select[name="'+e[0]+'[]"]').append('<option value="'+el[0]+'">'+el[1]+'</option>');
						});
					});
			} 
			else if(e[1] == 'int' || e[1] == 'double' || e[1] == 'float')
				form += '<input type="text" name ="'+e[0]+'"/><br>';
			else if(!isNaN(e[1]))	
				form += '<textarea name="'+e[0]+'" form="cu_form" required></textarea><br>'; 
			else if(e[1] == '*')
				form += '<input type="file" name="'+e[0]+'" id="file_'+e[0]+'" required> <div class="catcher" data-input="file_'+e[0]+'" ondragover="return false"><i class="fas fa-3x fa-arrow-alt-circle-down"></i><br><br><span class="catcherFilesLabel"></span><br>(Current file will persist if no new file is chosen)</div><br>';
			else if(e[1] == 'date')
				form += '<input name="'+e[0]+'" type="date" required><br>';
			else if(e[1] == 'boolean')
				form += ' <select name="'+e[0]+'"><option value="0">0-No</option><option value="1">1-Yes</option></select><br>';
			else{
				form += '<select name="'+e[0]+'"></select><br>';
				$.get('/src/crud_read.php?project='+window._projectName+'&table=' + e[1] + '&show=true', function(response){
					response = JSON.parse(response);
					$.each(response.data, function(i, el){
						$('select[name='+e[0]+']').append('<option value="'+el[0]+'">'+el[1]+'</option>');
					});
				});
			}
		}
		$('.form_element').append(form);
	});

	$('.form_element').append('<input type="submit" class="btn btn-primary" style="float:right" value="OK">');
	$('.form_element .catcher').each(function(i, el){
		el.addEventListener('drop', function(ev){
			ev.stopPropagation();
			ev.preventDefault();
			var file = ev.dataTransfer.files[0];
			var name = file.name;

			$(el).find('.catcherFilesLabel').text(name);
			$('#'+$(el).data('input'))[0].files = ev.dataTransfer.files;
			//$label.text(files.length > 1 ? ($input.attr('data-multiple-caption') || '').replace( '{count}', files.length ) : files[ 0 ].name);
		}, false);
	});
	$('.form_element').hide();
}

doTable = function(name, displayName, thenDoForm){
	window.name = name = name || window.name; 
	window.displayName = displayName = displayName || window.displayName; 

	$.get('/src/crud_read.php?project='+window._projectName+'&table=' + name, function(data){
		console.log(data);
		data = JSON.parse(data);

		if (typeof doTablePreHook === "function")
			doTablePreHook();

		if(data.error){
			doModal('error', data.error, 5000);
			return;
		}

		if( $.fn.DataTable.isDataTable('.table_element')){
			$('.table_element').DataTable().destroy();
		}

		$('.table_element').html('<thead><tr></tr></thead><tfoot><tr></tr></tfoot>');

		window.otherTables = [];
		window.otherTablesGotten = 0;
		$.each(data.columns, function(i, e){
			if(e[3] == 'multi' && !(name == 'portlet' && e[0] == 'query_tables'))
				otherTables[e[1]] = [];
		});
		if(Object.keys(otherTables).length == 0)
			doTable2(data, thenDoForm);
		$.each(Object.keys(otherTables), function(i, otherTables_e){
			$.get('/src/crud_read.php?project='+window._projectName+'&table=' + otherTables_e + '&show=true', function(response){
				response = JSON.parse(response);
				$.each(response.data, function(i, el){
					otherTables[otherTables_e][el[0]] = el[1];
				});
				otherTablesGotten++;
				if(otherTablesGotten == Object.keys(otherTables).length)
					doTable2(data, thenDoForm);
			});
		});
	});
}
doTable2 = function(data, thenDoForm){
	var columns = [];
	$.each(data.columns, function(i, e){
		$('tr').append('<th>'+(e[2] || 'ID')+'</th>');
		if(e['display'] == 'html')
			columns.push({});
		else if(e[1] == '*')
			columns.push({ "render": function (data, type, full, meta) {return "<a href='uploads/"+window.name+'/'+data+"'><img style='width:60px;' src='uploads/"+window.name+'/'+data+"'/></a>"; } });
		else if(e[3] == 'multi' && name == 'portlet' && e[0] == 'query_tables')
			columns.push({ "render": function (data, type, full, meta) {return '-'+JSON.parse(data).join('<br>-'); } });
		else if(e[3] == 'multi'){
			columns.push({ "render": function (data, type, full, meta) {
				data = JSON.parse(data);
				$.each(data, function(i, el){
					data[i] = otherTables[e[1]][el];
				});
				debugger;
				return '-'+data.join('<br>-'); 
			}});
		}
		else
			columns.push({ "render": $.fn.dataTable.render.text() });
	});
	columns.push({
		defaultContent: '<div class="row_buttons"><button onclick="handleEdit(this)" class="btn btn-primary btn-xs"><i class="fas fa-pencil-alt"></i></button><button onclick="handleDelete(this)" class="btn btn-danger btn-xs"><i class="fas fa-trash-alt"></i></button></div>'
	});
	$('tr').append('<th></th>');

	// DataTable
	$('.table_element').DataTable({
		"data": data.data,
		"columns": columns
	});
	$('TFOOT').remove();

	if (typeof doTablePostHook === "function")
		doTablePostHook();

	if(thenDoForm)
		doForm(data.columns);
}
$(document).ready(function() {
	var endpoint = {"create": "/src/crud_create.php", "update": "/src/crud_update.php", "delete":"/src/crud_delete.php"};
	$('.form_element').submit(function(e){
		e.preventDefault();

		if (typeof submitHook === "function")
			if(submitHook())
				return;

		var formData = new FormData($('.form_element')[0]);
		$.ajax({
			type: "POST",
			url: endpoint[window.crud_mode] + '?project='+window._projectName+'&table=' + window.name,
			data: formData,
			success: function(data) {
				response = JSON.parse(data);

				if(response.error)
					if(response.error == 'login')
						window.location = 'login.php';
					else
						doModal('error', response.error, 3000);
				else
					doModal('success', response.success, 800);

				doTable(undefined, undefined, false);
				toggleForm("close");
			},
			enctype: "multipart/form-data",
			contentType: "multipart/mixed; boundary=frontier",
			contentType: false,
			processData: false
		});
	});
	$('li>.tab:first').click();
	$('.sidebar_trigger').on('click', function () {
		$('.sidebarWrapper_sidebar').toggleClass('active');
		$(this).toggleClass('active');
		if(!$('.sidebarWrapper_sidebar').hasClass('active'))
			$('.sidebarWrapper_sidebar ul .collapse').removeClass('show')
	});
	doSidebarProjects();
});