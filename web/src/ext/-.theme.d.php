<?php
	require_once 'normalize_path.inc.php';

	$baseProjectUrl = '../projects/'.$config['_name'];
	$url = $baseProjectUrl.'/'.$row['url'];

	// error_log('normalize($url): '.normalize($url));
	// error_log('normalize($baseProjectUrl): '.normalize($baseProjectUrl));
	
	if(strpos(normalize($url), normalize($baseProjectUrl)) === 0 &&
		strpos(normalize($url), normalize($baseProjectUrl).'/admin') !== 0
	){
		foreach (json_decode($row['contents']) as $key => $value) {
			$command = 'rm "'.$url.'/'.$value.'"';
			error_log('Command rm: '.$command);
			exec($command);
		}
		$command = 'rmdir `find "../projects/'.$config['_name'].'" -type d | grep -v ../projects/'.$config['_name'].'/admin`';
		error_log('Command find: '.$command);
		exec($command);
	}
?>