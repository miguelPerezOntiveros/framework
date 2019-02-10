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
?>