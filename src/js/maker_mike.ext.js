console.log('extending from src');

$.getScript( "/vendor/yamljs/yaml.js", function( data, textStatus, jqxhr ){
 	console.log( "YAML lib loaded. Status: "+jqxhr.status );
});

function submitHook(e){
	if(window.name == 'project'){
		if($('textarea[name=yaml]').length == 0)
			$('.form_element').append('<textarea name="yaml" form="cu_form" style="display:none;" required></textarea><br>');
		$('textarea[name=yaml]').val($('textarea[name=config]').val());
		try {
		  	$('textarea[name=config]').val(
				JSON.stringify(YAML.parse($('textarea[name=config]').val()))
			);
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
			$("textarea[name='config']").text(data);
		});
		$('textarea[name=yaml]').addClass('d-none');
	}
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
