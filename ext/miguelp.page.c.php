<?php
	$url = '../projects/'.$config['_projectName'].'/'.$row['url'];
	$dir = dirname($url);
	exec('mkdir -p '.$dir.' && cp endpoint.php '.$url.' && ln -s '.$_SERVER["DOCUMENT_ROOT"].'/src/page.php '.$dir);
?>