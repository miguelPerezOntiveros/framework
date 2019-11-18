console.log('extending from src');

$.getScript( "/vendor/yamljs/yaml.js", function( data, textStatus, jqxhr ){
 	console.log( "YAML lib loaded. Status: "+jqxhr.status );
});

function submitHook(e){
	if(window.name == 'project' && window.crud_mode != 'delete'){
		// config is the one displayed, it will be updated to carry json. yaml is a textarea in which the yaml will travel
		if($('textarea[name=yaml]').length == 0)
			$('.form_element').append('<textarea name="yaml" form="cu_form" style="display:none;" required></textarea><br>');
		$('textarea[name=yaml]').val(editors['config'].getValue());
		try {
			editors['config'].setValue(JSON.stringify(YAML.parse(editors['config'].getValue())))
		}
		catch(err) {
			console.log('Invalid YAML');
			$('textarea[name=yaml]').remove();
			doModal('error', 'Invalid YAML', 1000);

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
$('table').on('click', '.copy_json_as_yaml', function(e){
	const el = document.createElement('textarea');
	el.value = YAML.stringify( JSON.parse($(e.target).parent().parent().text()) );
	el.setAttribute('readonly', '');
	el.style.position = 'absolute';
	el.style.left = '-9999px';
	document.body.appendChild(el);
	el.select();
	document.execCommand('copy');
	document.body.removeChild(el);
});
function doTablePostHook(){
	if(window.name == 'project'){
		console.log('in maker_mike project table post hook');
		doSidebarProjects();
		$('.row_buttons').each(function(i, e){
			$(e).find('button:first').remove();
		});
	}
}