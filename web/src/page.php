<?php 
	require 'db_connection.inc.php';
	$res = array();
	$page = '';
	
	$sql = 'SELECT * FROM page WHERE url = "'.$url.'";';
	error_log('INFO - sql:' .$sql);
	$stmt = $pdo->prepare($sql);
	$stmt->execute();

	if($pageRow = $stmt->fetch(PDO::FETCH_ASSOC))
		echo processPage($pageRow['html'], $pdo);

	function processPage($page, $pdo) {
		return preg_replace_callback('/<mm-p>.*?<\/mm-p>/', function ($matches) use (&$pdo) {
			$portletName = preg_replace('/<mm-p>(.*)<\/mm-p>/', '$1', $matches[0]);
			return processPortlet($portletName, $pdo);
		}, $page);
	}

	function processPortlet($portletName, $pdo) {
		$fields = array();
		$portlet = '';

		$sql = 'SELECT * FROM portlet WHERE name = "'.$portletName.'";';
		error_log('INFO - sql:' .$sql);
		$stmt = $pdo->prepare($sql);
		$stmt->execute();
		if($portletRow = $stmt->fetch(PDO::FETCH_ASSOC)){
			$fileds = [];
			$portlet = $portletRow['pre'];

			$sql = 'SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS  WHERE TABLE_SCHEMA="'.$GLOBALS['config']['_projectName'].'" AND TABLE_NAME IN ('.substr($portletRow['query_tables'], 1, -1).');';
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
				if($portlet != $portletRow['pre'])
					$portlet .= $portletRow['tween'];
				$portlet .= processTemplate($portletRow['template'], $templateRow);
			}
			$portlet .= $portletRow['post'];
		}
		return $portlet;
	}

	function processTemplate($template, $templateRow) {
		return preg_replace_callback('/<mm-v>.*?<\/mm-v>/', function ($matches) use (&$templateRow) {
			$variableName = preg_replace('/<mm-v>(.*)<\/mm-v>/', '$1', $matches[0]);
			return $templateRow[$variableName];
		}, $template);
	}
?>