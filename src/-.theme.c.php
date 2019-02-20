<?php
	require_once 'normalize_path.inc.php';

	$baseProjectUrl = '../projects/'.$config['_projectName'];
	$url = $baseProjectUrl.'/'.$row['url'];
	
	// error_log('normalize($url): '.normalize($url));
	// error_log('normalize($baseProjectUrl): '.normalize($baseProjectUrl));
	
	if(strpos(normalize($url), normalize($baseProjectUrl)) === 0 &&
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
				error_log('==');
				if(strpos(normalize($url).'/', normalize($baseProjectUrl.'/'.$row_checking_url_collisions['url']).'/') === 0)
					$url_available = false;
			}

		if($url_available){
			$command = 'unzip "'.$baseProjectUrl.'/admin/uploads/theme/'.$row['file'].'" -d '.$url;
			error_log('Deploying theme: '.$command);
			exec($command);

			$execOutput = array();
			error_log('pwd: '.exec('pwd'));
			$command = 'cd '.$baseProjectUrl.' && find '.$row['url'].' -type f';
			error_log('find: '.$command);
			exec($command, $execOutput);
			$row['contents'] = '';
			$files_in_theme = [];
			foreach($execOutput as $line) {
				error_log('line: '.$line);
				$files_in_theme[] = substr($line, 3);
			}
			$row['contents'] = json_encode($files_in_theme);
		}
	}
?>