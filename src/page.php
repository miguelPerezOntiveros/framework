<?php 
	require 'db_connection.inc.php';
	$res = array();
	$fields = array();
	$page = '';
	
	$sql = 'SELECT * FROM page WHERE url = "'.$url.'";';
	error_log('INFO - sql:' .$sql);
	if($result = $conn->query($sql)){
		if($pageRow = $result->fetch_assoc()){
			echo processPage($pageRow['html'], $conn);
		}
	}

	function processPage($page, $conn) {
		return preg_replace_callback('/<mm-p>.*?<\/mm-p>/', function ($matches) use (&$conn) {
				$portletName = preg_replace('/<mm-p>(.*)<\/mm-p>/', '$1', $matches[0]);
				return processPortlet($portletName, $conn);
		}, $page);
	}

	function processPortlet($portletName, $conn) {
		$portlet = '';
		$sql = 'SELECT * FROM portlet WHERE name = "'.$portletName.'";';
		error_log('INFO - sql:' .$sql);
		if($result = $conn->query($sql)){
			if($portletRow = $result->fetch_assoc()){
				$fileds = [];
				$sql = 'SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS  WHERE TABLE_SCHEMA="'.$GLOBALS['config']['_projectName'].'" AND TABLE_NAME IN ('.substr($portletRow['query_tables'], 1, -1).');';
				error_log('INFO - sql:' .$sql);
				if($result = $conn->query($sql)){
					while($fieldsRow = $result->fetch_assoc()){
						$fields[] = $fieldsRow['TABLE_NAME'].'.'.$fieldsRow['COLUMN_NAME'].' as "'.$fieldsRow['TABLE_NAME'].'.'.$fieldsRow['COLUMN_NAME'].'"';
					}
				}

				$portlet = $portletRow['pre'];
				$sql = 'SELECT '.implode(', ', $fields).' FROM '.implode(', ', json_decode($portletRow['query_tables'])).' WHERE '.$portletRow['query_conditions'].';';
				error_log('INFO - sql:' .$sql);
				if($result2 = $conn->query($sql)){
					while($templateRow = $result2->fetch_assoc()){
						if($portlet != $portletRow['pre'])
							$portlet .= $portletRow['tween'];
						$portlet .= processTemplate($portletRow['template'], $templateRow);
					}
				}
				$portlet .= $portletRow['post'];
			}
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