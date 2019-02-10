<?php
	require_once 'normalize_path.inc.php';

	$baseProjectUrl = '../projects/'.$config['_projectName'];
	$url = $baseProjectUrl.'/'.$row['url'];

	// error_log('normalize($url): '.normalize($url));
	// error_log('normalize($baseProjectUrl): '.normalize($baseProjectUrl));
	
	if(strpos(normalize($url), normalize($baseProjectUrl)) === 0 &&
		strpos(normalize($url), normalize($baseProjectUrl).'/admin') !== 0
	){
		$command = 'rm -rf '.$url;
		error_log('Deleting theme: '.$command);
		exec($command);
	}
?>