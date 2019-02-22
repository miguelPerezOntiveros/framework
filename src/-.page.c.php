<?php
	if(substr($row['url'], -4) != '.php')
		$row['url'] .= '.php';

	require_once 'normalize_path.inc.php';

	$baseProjectUrl = '../projects/'.$config['_projectName'];
	$url = $baseProjectUrl.'/'.$row['url'];
	$dir = dirname($url);

	// error_log('normalize($url): '.normalize($url));
	// error_log('normalize($baseProjectUrl): '.normalize($baseProjectUrl));
	
	if(strpos(normalize($url).'/', normalize($baseProjectUrl).'/') === 0 &&
		strpos(normalize($url), normalize($baseProjectUrl).'/admin') !== 0
	){
		$url_available = true;
		$sql = 'SELECT url FROM '.$_GET['table'].';';
		error_log('SQL - '.$config['_projectName'].' - ' .$sql);
		if(!$result = $conn->query($sql))
			exit(json_encode((object) ["error" => "Error while retrieving entry"]));
		else
			while($row_checking_url_collisions = $result->fetch_assoc()){
				error_log('existing url: '.normalize($baseProjectUrl.'/'.$row_checking_url_collisions['url']));
				error_log('contender url: '.normalize($url));
				if(strpos(normalize($url).'/', normalize($baseProjectUrl.'/'.$row_checking_url_collisions['url']).'/') === 0)
					$url_available = false;
			}

		if($url_available){
			error_log('Creating page');
			$command = 'mkdir -p '.$dir.' && cp endpoint.php '.$url.' && ln -s '.$_SERVER["DOCUMENT_ROOT"].'/src/page.php '.$dir;
			error_log('Command: '.$command);
			exec($command);
		} else {
			exit(json_encode((object) ["error" => 'Page on the same URL already exists.']));
		}
	}
?>