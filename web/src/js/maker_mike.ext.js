console.log('extending from src');

$.getScript( "/vendor/yamljs/yaml.js", function( data, textStatus, jqxhr ){
 	console.log( "YAML lib loaded. Status: "+jqxhr.status );
});

function submitHook(e){
	if(window.name == 'project' && window.crud_mode != 'delete'){
		// .form_element is the form that is being submited
		//if($('textarea[name=yaml]').length == 0)
		//	$('.form_element').append('<textarea name="yaml" form="cu_form" style="display:none;" required></textarea><br>');
		
		// editors['config'] will carry the user's config in JSON
		try {
			editors['config'].setValue(JSON.stringify(YAML.parse(editors['config'].getValue())))
		}
		catch(err) {
			console.log('Invalid YAML');
			$('textarea[name=yaml]').remove();
			doModal('error', 'Invalid YAML', 1000);

			// if we return anything, the form submition will not continue
			return 'Invalid YAML';
		}
	}
}
$( ".form_plus_button" ).on('click', function(){
	if(window.name == 'project'){
		if($('textarea[name=yaml]').length != 0)
			$('textarea[name=yaml]').remove();
		$.get('/default.yml', function(data){
			editors['config'].setValue(data);
		});
		$('textarea[name=yaml]').addClass('d-none');
	}
});
$('table').on('click', '.copy', function(e){
	const el = document.createElement('textarea');
	var id = $(e.target).closest('div').parent().parent().parent().parent().find('td:first').html();
	var format = $(e.target).closest('div').parent().text();
	el.value = window.configs[id][format];

	el.setAttribute('readonly', '');
	el.style.position = 'absolute';
	el.style.left = '-9999px';
	document.body.appendChild(el);
	el.select();
	document.execCommand('copy');
	document.body.removeChild(el);
	console.log('copied. id: '+id+' format:'+format);
});
$('table').on('click', '.export', function(e){
	var content_only = '';
	if($(e.target).closest('button').hasClass('content_only'))
		content_only = '&content_only=1';
	var id = $(e.target).closest('div').parent().parent().parent().parent().find('td:first').html();
	$.get('/src/export.php?project='+JSON.parse(window.configs[id]['JSON']).name+content_only, function(response){
		console.log('export response: ' + response);

		element = document.createElement('a');
		element.setAttribute('href', '../../'+JSON.parse(response).path+JSON.parse(response).file);
		element.setAttribute('download', JSON.parse(response).file);
		element.style.display = 'none';
		document.body.appendChild(element);
		element.click();
		document.body.removeChild(element);
	});
});
function doTablePostHook(){
	if(window.name == 'project'){
		console.log('in maker_mike project table post hook');
		doSidebarProjects();
		if($('.row_buttons').find('.row_option:last div button i').hasClass('fa-pencil-alt')){
			$('.row_buttons').find('button').css('float', 'left');
			$('.row_buttons').find('.row_option:last').remove();
			$('.row_buttons').append('<div class="row_option"><div style="display: flex"><button style="float: left" class="btn btn-primary btn-xs copy"><i class="fas fa-copy"></i></button></div><p>JSON</p></div>');
			$('.row_buttons').append('<div class="row_option"><div style="display: flex"><button style="float: left" class="btn btn-primary btn-xs copy"><i class="fas fa-copy"></i></button></div><p>YAML</p></div>');			
			$('.row_buttons').append('<div class="row_option"><div style="display: flex"><button style="float: left" class="btn btn-primary btn-xs export content_only"><i class="fas fa-angle-down"></i></button></div><p></p></div>');
			$('.row_buttons').append('<div class="row_option"><div style="display: flex"><button style="float: left" class="btn btn-primary btn-xs export"><i class="fas fa-angle-double-down"></i></button></div><p></p></div>');
		}

	}
}
function doTablePreHook(data){
	window.configs = [];
	if(window.name == 'project'){
		$.each(data.data, function(i, e){
			window.configs[e[0]] = [];
			window.configs[e[0]]['JSON'] = e[2];
			config = JSON.parse(e[2]);
			window.configs[e[0]]['YAML'] = YAML.stringify(config);

			e[2] = config['show'] + ' ('+config['name']+')';
		});
		$('.copy i').attr('data-after','bar');
	}
	return data;
}
function doToggleHook(){
	$('.import_form').toggleClass('bs-callout-left');
	$('.import_form').toggle('slow');
}

function doFormPostHook(){
	$('.table_parent').before('<div class="col-12" style="padding-top: 1em;"></div><div class="col-12 import_form"></div>');
	$('.import_form').append('<b>File:</b><br>\
		<form class="import_form_element">\
			<input type="file" name="import_file" id="import_file" required> <div class="catcher" data-input="import_file" ondragover="return false"><i class="fas fa-3x fa-arrow-alt-circle-down"></i><br><br><span class="catcherFilesLabel"></span><br>Select a zip file containing a modified export, it can be a complete or data-only export.</div><br>\
			<input type="submit" class="btn btn-primary" style="float:right" value="Import">\
		</form>\
	</div>');
	$('.table_parent').before('<hr>');
	$('.import_form').hide();

	$('.import_form_element .catcher').each(function(i, el){
		el.addEventListener('drop', function(ev){
			ev.stopPropagation();
			ev.preventDefault();
			var file = ev.dataTransfer.files[0];
			var name = file.name;

			$(el).find('.catcherFilesLabel').text(name);
			$('#import_file')[0].files = ev.dataTransfer.files;
		}, false);
	});
}