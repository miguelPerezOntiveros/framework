<?php 
	require $_SERVER["DOCUMENT_ROOT"].'/src/set_config_and_params.inc.php';

	$segments = explode("/", $_SERVER['REQUEST_URI']);
	$url = implode("/", array_slice($segments, 3));

	require $_SERVER["DOCUMENT_ROOT"].'/src/db_connection.inc.php';
	$res = array();
	$page = '';
	
	$sql = 'SELECT * FROM page WHERE url = "'.urldecode($url).'";';
	error_log('INFO - sql:' .$sql);
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	if($pageRow = $stmt->fetch(PDO::FETCH_ASSOC))
		echo processPage($pageRow['html'], $pdo);

	function processPage($html, $pdo) {
		$images = [];
		$sql = 'SELECT * FROM image;';
		error_log('INFO - sql:' .$sql);
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		while($imagesRow = $stmt->fetch(PDO::FETCH_ASSOC)){
			$images[$imagesRow['name']] = $imagesRow['image'];
		}
		$texts = [];
		$sql = 'SELECT * FROM text;';
		error_log('INFO - sql:' .$sql);
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		while($textsRow = $stmt->fetch(PDO::FETCH_ASSOC)){
			$texts[$textsRow['name']] = $textsRow['text'];
		}

		// result = preg_replace_callback(pattern, callback(array_of_matches)->string, subject_string);
		// result = preg_replace(pattern, replacement, subject_string);
		$html = preg_replace_callback('/<mm-p>.*?<\/mm-p>/', function ($matches) use (&$pdo) {
			$portletName = preg_replace('/<mm-p>(.*)<\/mm-p>/', '$1', $matches[0]);
			return processPortlet($portletName, $pdo);
		}, $html);
		$html = preg_replace_callback('/<mm-i>.*?<\/mm-i>/', function ($matches) use (&$images){
			$imageName = preg_replace('/<mm-i>(.*)<\/mm-i>/', '$1', $matches[0]);
			return '/projects/'.$GLOBALS['config']['_name'].'/admin/uploads/image/'.$images[$imageName];
		}, $html);
		$html = preg_replace_callback('/<mm-t>.*?<\/mm-t>/', function ($matches) use (&$texts){
			$textName = preg_replace('/<mm-t>(.*)<\/mm-t>/', '$1', $matches[0]);
			return $texts[$textName];
		}, $html);
		return $html;
	}

	function processPortlet($portletName, $pdo) {
		$fields = array();
		$html = '';

		$sql = 'SELECT * FROM portlet WHERE name = "'.$portletName.'";';
		error_log('INFO - sql:' .$sql);
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		if($portletRow = $stmt->fetch(PDO::FETCH_ASSOC)){
			$fileds = [];
			$html = $portletRow['pre'];

			// populate $fields
			$sql = 'SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS  WHERE TABLE_SCHEMA="'.$GLOBALS['config']['_name'].'" AND TABLE_NAME IN ('.substr($portletRow['query_tables'], 1, -1).');';
			error_log('INFO - sql:' .$sql);
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			while($fieldsRow = $stmt->fetch(PDO::FETCH_ASSOC)){
				$fields[] = $fieldsRow['TABLE_NAME'].'.'.$fieldsRow['COLUMN_NAME'].' as "'.$fieldsRow['TABLE_NAME'].'.'.$fieldsRow['COLUMN_NAME'].'"';
			}
			
			$sql = 'SELECT '.implode(', ', $fields).' FROM '.implode(', ', json_decode($portletRow['query_tables'])).' WHERE '.$portletRow['query_conditions'].';';
			error_log('INFO - sql:' .$sql);
			$stmt = $pdo->prepare($sql);
			$stmt->execute();
			while($templateRow = $stmt->fetch(PDO::FETCH_ASSOC)){
				if($html != $portletRow['pre'])
					$html .= $portletRow['tween'];
				$html .= preg_replace_callback('/<mm-v>.*?<\/mm-v>/', function ($matches) use (&$templateRow) {
					$variableName = preg_replace('/<mm-v>(.*)<\/mm-v>/', '$1', $matches[0]);
					return $templateRow[$variableName];
				}, $portletRow['template']);
			}
			$html .= $portletRow['post'];
		}
		return $html;
	}
?>