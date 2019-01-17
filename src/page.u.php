<?php
	$url = '../projects/'.$config['_projectName'].'/'.$row_old['url'];
	$dir = dirname($url);
	exec('rm '.$url.' '.$dir.'/page.php && find ../projects/'.$config['_projectName'].' -path "../projects/'.$config['_projectName'].'/admin/*" -prune -o -type d -empty -delete');
	
	$url = '../projects/'.$config['_projectName'].'/'.$row['url'];
	$dir = dirname($url);
	exec('mkdir -p '.$dir.' && cp endpoint.php '.$url.' && ln -s '.$_SERVER["DOCUMENT_ROOT"].'/src/page.php '.$dir);
?>