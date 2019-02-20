<?php
	require_once 'normalize_path.inc.php';

	$baseProjectUrl = '../projects/'.$config['_projectName'];
	$url = $baseProjectUrl.'/'.$row['url'];
	$dir = dirname($url);

	// error_log('normalize($url): '.normalize($url));
	// error_log('normalize($baseProjectUrl): '.normalize($baseProjectUrl));
	
	if(strpos(normalize($url).'/', normalize($baseProjectUrl).'/') === 0 &&
		strpos(normalize($url), normalize($baseProjectUrl).'/admin') !== 0
	){
		error_log('Creating page');
		exec('mkdir -p '.$dir.' && cp endpoint.php '.$url.' && ln -s '.$_SERVER["DOCUMENT_ROOT"].'/src/page.php '.$dir);
	}
?>