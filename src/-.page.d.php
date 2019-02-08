<?php
	require_once '-.page.commons.php';

	$baseProjectUrl = '../projects/'.$config['_projectName'];
	$url = $baseProjectUrl.'/'.$row['url'];
	$dir = dirname($url);

	error_log('normalize($url): '.normalize($url));
	error_log('normalize($baseProjectUrl): '.normalize($baseProjectUrl));
	
	if(strpos(normalize($url), normalize($baseProjectUrl)) === 0 &&
		strpos(normalize($url), normalize($baseProjectUrl).'/admin') === false
	){
		error_log('deleting page');
		exec('rm '.$url.' '.$dir.'/page.php && find ../projects/'.$config['_projectName'].' -path "../projects/'.$config['_projectName'].'/admin/*" -prune -o -type d -empty -delete');
	}
?>