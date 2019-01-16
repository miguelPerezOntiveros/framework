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
		return preg_replace_callback('/<make-mike-portlet>.*?<\/make-mike-portlet>/', function ($matches) use (&$conn) {
				$portletName = preg_replace('/<make-mike-portlet>(.*)<\/make-mike-portlet>/', '$1', $matches[0]);
				return processPortlet($portletName, $conn);
		}, $page);
	}

	function processPortlet($portletName, $conn) {
		$portlet = '';
		$sql = 'SELECT * FROM portlet WHERE name = "'.$portletName.'";';
		error_log('INFO - sql:' .$sql);
		if($result = $conn->query($sql)){
			if($portletRow = $result->fetch_assoc()){
				$portlet = $portletRow['pre'];
				$sql = $portletRow['query'];
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
		return preg_replace_callback('/<make-mike-variable>.*?<\/make-mike-variable>/', function ($matches) use (&$templateRow) {
			$variableName = preg_replace('/<make-mike-variable>(.*)<\/make-mike-variable>/', '$1', $matches[0]);
			return $templateRow[$variableName];
		}, $template);
	}
?>