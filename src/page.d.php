<?php
	function normalize($path) {
	    $root = ($path[0] === '/') ? '/' : '';
	    $segments = explode('/', trim($path, '/'));
	    $res = [];
	    foreach($segments as $segment){
	        if (($segment == '.') || strlen($segment) === 0) {
	            continue;
	        }
	        if ($segment == '..') {
	        	if(empty($res))
	            	array_push($res, $segment);	
	            else
	            	array_pop($res);
	        } else {
	            array_push($res, $segment);
	        }
	    }
	    return $root . implode('/', $res);
	}
	
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