<?php
	isset($_GET['table']) || exit('No table requested');
	
	require 'config.inc.php';
	require 'session.inc.php';

	//Validating permission to table
	if(!preg_match($config['tables'][$_GET['table']]['permisions'], $_SESSION['type']))
		exit('No such table');
	
	require 'db_connection.inc.php';

	//TODO: get tables to join

	//TODO: empty permisions should be equal to /.*/ on tables
	//TODO: empty permisions should be equal to /.*/ on columns

	// Iterate through columns
	$allowedColumns = [];
	$toTraverse = $config['tables'][$_GET['table']]['columns'];
	reset($toTraverse);
	while ($column = current($toTraverse)) {
		if(preg_match( $toTraverse[key($toTraverse)]['permisions'], $_SESSION['type']))
			$allowedColumns[] = key($toTraverse);
		next($toTraverse);
	}

	$sql = 'SELECT '.implode(",", $allowedColumns).' from '.$_GET['table'];
	echo '   .SQL: ' .$sql.'     .  ';
	$res = array();

	if($result = $conn->query($sql)) {
		while($row = $result->fetch_assoc()) {
			$res[] = $row;
		}
	}
	echo json_encode($res);
	$conn->close();
?>








<?php
// 	$iguales = '';
// 	$joins = '';
// 	$tablasJoin = '';
// 	$busquedaRef = '';
// 	for($i = 0; $i<$campos; $i++)
// 		if($hereda[$i] != '' && $hereda[$i] != '*')
// 		{
// 			$joins = $joins.', t'.$i.'.'.$human[$hereda[$i]].' AS ref'.$i;
// 			$tablasJoin = $tablasJoin.', '.$seccion[$hereda[$i]].' AS t'.$i;
// 			$iguales = $iguales.'t'.$i.'.id = '.$tabla.'.'.$campo[$i].' AND ';
// 			$busquedaRef = $busquedaRef.'t'.$i.'.'.$human[$hereda[$i]]." like '%".$_GET['nombre']."%' or ";
// 		}
// 	$iguales = substr($iguales, 0, -4); // le quita el ultimo and
// 	$busquedaRef = ' or '.substr($busquedaRef, 0, -3); // le quita el ultimo or

// 	$baseRef = 'SELECT '.$tabla.'.*'.$joins.' FROM '.$tabla.$tablasJoin.' WHERE '.$iguales;
// 	$base = 'SELECT '.$tabla.'.* FROM '.$tabla;
// 	$limites = ' limit '.$limite_inferior.' , '.$epp.';';

	
// 	$forConBusqueda = '';
// 	for($i = 0; $i<$campos; $i++)
// 		$forConBusqueda = $forConBusqueda.$tabla.'.'.$campo[$i]." like '%".$_GET['nombre']."%' or ";//campo[i]
// 	if($joins != '')
// 		$forConBusqueda = ' ('.substr($forConBusqueda, 0, -3).$busquedaRef.')'; // le quita el ultimo or
// 	else
// 		$forConBusqueda = ' ('.substr($forConBusqueda, 0, -3).')'; // le quita el ultimo or

// 	if ($joins != '')	if(isset($_GET['nombre']))	$query = $baseRef.' AND '.$forConBusqueda.$order.$limites;
// 						else  						$query = $baseRef.$order.$limites;
// 	else 				if(isset($_GET['nombre']))	$query = $base.' WHERE'.$forConBusqueda.$order.$limites;
// 						else  						$query = $base.$order.$limites;
// 	include '../conexionBD.php';
// 	if(!$rs = mysql_query($query)) echo 'Error al cargar datos'.mysql_error().'|'.$query.'|'.$joins.'|'.isset($_GET['nombre']);
// 	if(isset($_GET['nombre']) && mysql_num_rows($rs) == 0) echo 'No se encontraron entradas<br>';
// ?>