
$base_url = 'http://127.0.0.1:8085/src/';
$.get($base_url+'/crud_read.php?project=maker_mike&table=project', function(response){
	$('#res').html(response);
})
