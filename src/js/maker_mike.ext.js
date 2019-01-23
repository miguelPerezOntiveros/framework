console.log('extending from src');

$.getScript( "/vendor/yamljs/yaml.js", function( data, textStatus, jqxhr ) {
  console.log( "YAML lib loaded. Status: "+jqxhr.status );
});

$('#cu_form').submit(function(e){
	$('textarea[name=config]').val(
		JSON.stringify(YAML.parse($('textarea[name=config]').val()))
	);
});