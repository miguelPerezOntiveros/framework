<?php
	$url = '../projects/'.$config['_projectName'].'/'.$row['url'];
	$dir = dirname($url);
	exec('rm '.$url.' '.$dir.'/page.php && find ../projects/'.$config['_projectName'].' -path "../projects/'.$config['_projectName'].'/admin/uploads/*" -prune -o -type d -empty -delete');
?>