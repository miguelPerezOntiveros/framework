console.log('extending from src');

$.getScript( "/vendor/yamljs/yaml.js", function( data, textStatus, jqxhr ) {
  console.log( "YAML lib loaded. Status: "+jqxhr.status );
});

$('#cu_form').submit(function(e){
	$('.form_element').append('<textarea  name="yaml" form="cu_form" required></textarea><br>');
	$('textarea[name=yaml]').val($('textarea[name=config]').val());
	$('textarea[name=config]').val(
		JSON.stringify(YAML.parse($('textarea[name=config]').val()))
	);
});
$( ".form_plus_button" ).on('click', function() {
	$.get('/default.yml', function(data){
		$("textarea[name='config']").text(data);
	});
	//$('textarea[name=yaml]').addClass('d-none');
});

