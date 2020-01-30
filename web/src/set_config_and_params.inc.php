<?php 
	function transform($input){
		$output = array();
		foreach($input as $project_property_key => $project_property) {
			if($project_property_key != 'tables')
				$output['_'.$project_property_key] = $project_property;
		}
		foreach($input['tables'] as $table){
			if(isset($table['name'])){
				$output[$table['name']] = array();
				foreach($table as $table_property_key => &$table_property) {
					if($table_property_key != 'name' && $table_property_key != 'columns')
						$output[$table['name']]['_'.$table_property_key] = $table_property;
					if($table_property_key == 'columns')
						foreach($table_property as $column_key => &$column) {
							if(isset($column['name'])){
								$output[$table['name']][$column['name']] = array();
								foreach ($column as $column_property_key => $column_property) {
									if($column_property_key != 'name')
										$output[$table['name']][$column['name']][$column_property_key] = $column_property;
								}
							}
							else // default column
								$output[$table['name']][$column] = array();
						}
				}
			}
			else // default table
				$output[$table] = array();
		}
		return $output;
	}			
	/* END OF FUNCTION DECLARATIONS*/

	if(!isset($config)){
		if(isset($_GET['project']))
			$project = $_GET['project'];
		else{
			preg_match('/\/projects\/(.*?)\/(.*)/', $_SERVER['REQUEST_URI'], $project);
			$project = $project[1];
		}
		error_log("project: ".$project);

		//Executing PDO
		$config['_name'] = 'maker_mike'; // force db_connection.inc.php to connect to the 'maker_mike' DB
		require 'db_connection.inc.php';
		$data = array();
		$columns = array();
		$sql = 'SELECT config FROM project WHERE JSON_EXTRACT(config, "$.name") = ?;';	
		error_log('SQL - '.$config['_name'].' - ' .$sql."\n");
		$stmt = $pdo->prepare($sql);
		$stmt->execute([$project]);
		if(!$row = $stmt->fetch(PDO::FETCH_NUM))
			exit(json_encode((object) ["error" => "Project ".$project." has not been set up"]));
		else{
			$config=json_decode($row[0], true);
		}
		$config = transform($config);
		require 'db_connection.inc.php';
	} else{
		error_log('invoked from backend');

		if(isset($GET_PARAMS))
			$_GET = $GET_PARAMS;
		if(isset($POST_PARAMS))
			$_POST = $POST_PARAMS;
	}
?>