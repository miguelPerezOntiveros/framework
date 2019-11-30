<?php
	require_once 'normalize_path.inc.php';

	$baseProjectUrl = '../projects/'.$config['_name'];
	$url = $baseProjectUrl.'/'.$row['url'];
	$dir = dirname($url);

	// error_log('normalize($url): '.normalize($url));
	// error_log('normalize($baseProjectUrl): '.normalize($baseProjectUrl));
	
	if(strpos(normalize($url), normalize($baseProjectUrl)) === 0 &&
		strpos(normalize($url), normalize($baseProjectUrl).'/admin') !== 0
	){
		error_log('Deleting page');
		exec('rm '.$url.' && find "../projects/'.$config['_name'].'" -path "../projects/'.$config['_name'].'/admin/*" -prune -o -type d -empty -delete');
	}
?>