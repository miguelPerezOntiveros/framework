<?php
	require_once 'normalize_path.inc.php';

	$baseProjectUrl = '../projects/'.$config['_projectName'];
	$url = $baseProjectUrl.'/'.$row['url'];

	// error_log('normalize($url): '.normalize($url));
	// error_log('normalize($baseProjectUrl): '.normalize($baseProjectUrl));
	
	if(strpos(normalize($url), normalize($baseProjectUrl)) === 0 &&
		strpos(normalize($url), normalize($baseProjectUrl).'/admin') !== 0
	){
		foreach (json_decode($row['contents']) as $key => $value) {
			$command = 'rm "'.$url.'/'.end(explode(' ', $value)).'"';
			error_log('Command: '.$command);
			exec($command);
		}
		$command = 'find "'.$baseProjectUrl.'" -path "../projects/'.$config['_projectName'].'/admin/*" -prune -o -type d -empty -delete';
		error_log('Command: '.$command);
		exec($command);
	}
?>