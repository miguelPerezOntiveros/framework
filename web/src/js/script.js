handleCreate = function(){
	window.crud_mode = 'create';
	toggleForm();
	$('input[type=file]').each(function(i, e){
		e.required = true;
	});
	if(name == 'portlet')
		$('select[name="query_tables[]"]').trigger('change');
}
handleEdit = function(e){
	window.crud_mode = 'update';
	toggleForm("open");
	$('input[type=file]').each(function(i, e){
		e.required = false;
	});
	var values = [];
	$(e).parent().parent().parent().parent().parent().find('td').slice(0, -1).each(function(i, e) {
		values.push($(e).text());
	})
	console.log(values);
	$('.form_element').find('textarea, pre, select, input[type!="submit"]').not('.ace_text-input').each(function(i, e){
		if($(e).is('select[multiple]'))
			$(e).val(values[i].split('-').slice(1));
		else if($(e).is('select'))
			$(e).val(values[i].split('-')[0]);
		else if($(e).attr('type') == 'file')
			; // don't fill in value for files
		else if($(e).is('pre'))
			editors[$(e).attr('name')].setValue(values[i]);
		else
			$(e).val(values[i]);
	})
	if(name == 'portlet')
		$('select[name="query_tables[]"]').trigger('change');
}
handleDelete = function(e){
	window.crud_mode = 'delete';
	var id = $($(e).parent().parent().parent().parent().parent().find('td')[0]).text();
	$('.form_element input[name=id]').val(id);
	$('.form_element').trigger('submit');
}
resetEditors = function(){
	for(prop in editors){
		editors[prop].setValue('');
	}
	window.editors = new Array();
	Array.from(document.getElementsByClassName('ace')).forEach(function(el){
	    editor = ace.edit(el, {
	    	wrap: true,
	    	maxLines:30,
	    	minLines: 3}
	    );
	    editor.setTheme("ace/theme/monokai");
	    editor.session.setMode({path:"ace/mode/php"});
	    setTimeout(function(){
	    	editor.resize();
	    	editor.gotoLine(0,1);
	    }, 600);
	    window.editors[el.getAttribute('name')] = editor;
	});
}
toggleForm = function (only){
	if(!only || 
		only == "close" && $('.form').hasClass('bs-callout-left') ||
		only == "open" && !$('.form').hasClass('bs-callout-left')){
		$('.form_element')[0].reset();
		$('.catcherFilesLabel').text('Drop file here');
		$('.form_element').toggle('slow');
		resetEditors();
		if ($('.form').toggleClass('bs-callout-left'))
		$('.form_plus').toggleClass('rotated');
	}
}

$.urlParam = function (name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)')
                      .exec(window.location.search);

    return (results !== null) ? results[1] || 0 : false;
}

loadSection = function(name, displayName, replaceState){
	$('.form').removeClass('bs-callout-left');
	$('.form_plus').removeClass('rotated');
	doTable(name, displayName, true);
	doMenu(name, displayName);
	if(replaceState){
		history.replaceState({}, "", "?sidebar="+$.urlParam('sidebar')+'&table='+name);
		//$('.sidebarWrapper_sidebar a').attr('href', '/index.php?sidebar=1&table='+name);
	}
}

doMenu = function(name, displayName){
	$('.tab').removeClass('active');
	$('#menu_'+name).addClass('active');
	$('#title').text(displayName);
}

doForm = function(columns){
	$('.form_element').html('');
	$.each(columns, function(i, e){
		var form = '';
		if(i==0) // The id row will be hidden to the user
			form += '<input type="hidden" name ="'+e[0]+'"/><br>';
		else{
			form += '<b>'+e[2]+':</b>'
			if(e[3] == 'tables'){
				$('.form_element').append(form+'</br><select name="'+e[0]+'[]" multiple required></select><br>');
				form = '';
				$.each($('.navbar-nav li span').slice(0, -1), function(i, el){
					$('select[name="'+e[0]+'[]"]').append('<option data-table_name="'+$(el).data('table')+'" value="'+$(el).data('table')+'">'+$(el).text()+'</option>');
				});
			}
			else if(e[3] == 'multi'){
				$('.form_element').append(form+'</br><select name="'+e[0]+'[]" multiple required></select><br>');
				form = '';
				$.get('/src/crud_read.php?project='+window._projectName+'&table=' + e[1] + '&show=true', function(response){
					response = JSON.parse(response);
					$.each(response.data, function(i, el){
						$('select[name="'+e[0]+'[]"]').append('<option value="'+el[0]+'">'+el[1]+'</option>');
					});
				});
			} 
			else if(e[1] == 'int' || e[1] == 'double' || e[1] == 'float')
				form += '</br><input type="text" name ="'+e[0]+'"/><br>';
			else if(!isNaN(e[1])){
				if(name == 'page' && e[0] == 'html'){
					form += '<span style="float:right" class="dropdown show"><a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Insert Portlet</a><div class="dropdown-menu cu_form-portlet_options" aria-labelledby="dropdownMenuLink"></div></span>';
					$('.form_element').append(form);
					form = '';
					$.get('/src/crud_read.php?project='+window._projectName+'&table=portlet' + '&show=true', function(response){
						response = JSON.parse(response);
						if(!response.data.length)
							$('.cu_form-portlet_options').append('<a class="dropdown-item" href="#">No portlet available</a>');
						$.each(response.data, function(i, el){
							$('.cu_form-portlet_options').append('<a class="dropdown-item cu_form-insert_portlet" href="#">'+el[1]+'</a>');
						});
					});					
				}
				if(name == 'portlet' && e[0] == 'template'){
					form += '<span style="float:right" class="dropdown show"><a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Insert Variable</a><div class="dropdown-menu cu_form-variable_options" aria-labelledby="dropdownMenuLink"></div></span>';
					$('.form_element').append(form);
					$('.cu_form-variable_options').append('<a class="dropdown-item" href="#">No table selected</a>');
					form = '';
					window.form_portlet_variable_options = [];
					$.each($('select[name="query_tables[]"] option'), function(i, option){
						$.get('/src/crud_read.php?project='+window._projectName+'&table=' + $(option).data('table_name') + '&columns=', function(response){
							window.form_portlet_variable_options[$(option).data('table_name')] = [];
							$.each(JSON.parse(response).columns, function(i, column){
								window.form_portlet_variable_options[$(option).data('table_name')].push($(option).data('table_name') + '.' + column[0]);
							});
						});
					});
				}
				if(e[1]<260)
					form += '</br><textarea name="'+e[0]+'" form="cu_form" rows="1" class="single_lined" required></textarea><br>';				
				else
					form += '</br><pre name="'+e[0]+'" class="ace"></pre><br>';
			}
			else if(e[1] == 'JSON')
				form += '</br><pre name="'+e[0]+'" class="ace"></pre><br>';
			else if(e[1] == 'file')
				form += '</br><input type="file" name="'+e[0]+'" id="file_'+e[0]+'" required> <div class="catcher" data-input="file_'+e[0]+'" ondragover="return false"><i class="fas fa-3x fa-arrow-alt-circle-down"></i><br><br><span class="catcherFilesLabel"></span><br>(Current file will persist if no new file is chosen)</div><br>';
			else if(e[1] == 'date')
				form += '</br><input name="'+e[0]+'" type="date" required><br>';
			else if(e[1] == 'boolean')
				form += '</br><select name="'+e[0]+'"><option value="0">0-No</option><option value="1">1-Yes</option></select><br>';
			else{
				form += '</br><select name="'+e[0]+'"></select><br>';
				$.get('/src/crud_read.php?project='+window._projectName+'&table=' + e[1] + '&show=true', function(response){
					response = JSON.parse(response);
					$.each(response.data, function(i, el){
						$('select[name='+e[0]+']').append('<option value="'+el[0]+'">'+el[1]+'</option>');
					});
				});
			}
		}
		$('.form_element').append(form);
		resetEditors();
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
			data = doTablePreHook(data);

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
			if(e[3] == 'multi')
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
		else if(e[1] == 'file')
			columns.push({ "render": function (data, type, full, meta) {return "<a href='uploads/"+window.name+'/'+data+"'><img style='width:60px;' src='uploads/"+window.name+'/'+data+"'/></a>"; } });
		else if(e[3] == 'tables')
			columns.push({ "render": function (data, type, full, meta) {return '-'+JSON.parse(data).join('<br>-'); } });
		else if(e[3] == 'multi'){
			columns.push({ "render": function (data, type, full, meta) {
				data = JSON.parse(data);
				$.each(data, function(i, el){
					data[i] = otherTables[e[1]][el] || '[Broken ref to id ' + el + ']';
				});
				return '-'+data.join('<br>-'); 
			}});
		}
		else
			columns.push({ "render": $.fn.dataTable.render.text() });
	});
	columns.push({
		defaultContent: '<div class="row_buttons">'+
				'<div class="row_option"><div style="display: flex"><button onclick="handleDelete(this)" class="btn btn-danger btn-xs"><i class="fas fa-trash-alt"></i></button></div></div>'+
				'<div class="row_option"><div style="display: flex"><button onclick="handleEdit(this)" class="btn btn-primary btn-xs"><i class="fas fa-pencil-alt"></i></button></div></div>'+
			'</div>'
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
	window.editors = new Array();
	$('.form_element').submit(function(e){
		e.preventDefault();

		if (typeof submitHook === "function")
			if(submitHook())
				return;

		var formData = new FormData($('.form_element')[0]);
		for(prop in editors)
			formData.append(prop, editors[prop].getValue());

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
	if(!$.urlParam('table'))
		$('li>.tab:first').click();
	else
		$('li>.tab[data-table='+$.urlParam('table')+']').click();
		loadSection($.urlParam('table'));
	$('.sidebar_trigger').on('click', function() {
		if(window.innerWidth < 768){
			$('.sidebarWrapper_sidebar').toggleClass('applyMediaQuery_sidebarWrapper_sidebar');
			$('.sidebar_trigger').toggleClass('applyMediaQuery_sidebar_trigger');
		}
		else{
			$('.sidebarWrapper_sidebar').toggleClass('active');
			$(this).toggleClass('active');
			if($('.sidebarWrapper_sidebar').hasClass('active')){
				history.replaceState({}, '', '?sidebar=0&table='+$.urlParam('table'));
				$('.sidebarWrapper_sidebar').removeClass('applyMediaQuery_sidebarWrapper_sidebar');
				$('.sidebar_trigger').removeClass('applyMediaQuery_sidebar_trigger');
			}
			else{
				$('.sidebarWrapper_sidebar ul .collapse').removeClass('show');
				history.replaceState({}, '', '?sidebar=1&table='+$.urlParam('table'));
				$('.sidebarWrapper_sidebar').addClass('applyMediaQuery_sidebarWrapper_sidebar');
				$('.sidebar_trigger').addClass('applyMediaQuery_sidebar_trigger');
			}
		}
	});
	document.getElementsByClassName('topbar_logout_link')[0].onclick = function() {
		location.href='login.php?sidebar='+$.urlParam('sidebar'); 
	};

	$('.nav-item:not(.dropdown)').on('click', function(){
		if(!$('.topbar_trigger').hasClass('collapsed'))
			$('.topbar_trigger').click();
	})
	$('#cu_form').on('click', '.cu_form-insert_portlet', function(e){
		editor = editors['html'];
		editor.session.insert(editor.getCursorPosition(), '<mm-p>' + $(e.target).text() + '</mm-p>');
	});
	$('#cu_form').on('click', '.cu_form-insert_variable', function(e){
		editor = editors['template'];
		editor.session.insert(editor.getCursorPosition(), '<mm-v>' + $(e.target).text() + '</mm-v>');
	});
	$('#cu_form').on('change', 'select[name="query_tables[]"]', function(){
		$('.cu_form-variable_options').html('');
		$.each($('select[name="query_tables[]"]').val(), function(i, selected_table){
			$.each(window.form_portlet_variable_options[selected_table], function(i, entry_from_selected_table){
				$('.cu_form-variable_options').append('<a class="dropdown-item cu_form-insert_variable" href="#">'+entry_from_selected_table+'</a>');
			});
		});
		if(!$('select[name="query_tables[]"]').val().length)
			$('.cu_form-variable_options').append('<a class="dropdown-item" href="#">No table selected</a>');
	})
	doSidebarProjects();
});