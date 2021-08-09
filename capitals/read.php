<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/capital.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	function read($get){
		$db = new Capital();
		$mode = $get["mode"];		
		$limit = defaultMapValue($get, "limit", null);
		
		$where = array();
		
		if(array_key_exists("start_time", $get)){
			$where[] = Database::sqlGe("TIME", "'" . $get["start_time"] . "'");
		}
		
		if(array_key_exists("end_time", $get)){
			$where[] = Database::sqlLe("TIME", "'" . $get["end_time"] . "'");
		}
		
		if(array_key_exists("user", $get)){
			$where[] = Database::sqlEq("USER", "'" . $get["user"] . "'");
		}
		
		if(array_key_exists("type", $get)){
			$where[] = Database::sqlEq("TYPE", "'" . $get["type"] . "'");
		}
		
		$sql_where = null;
		
		if(count($where) > 0){
			$sql_where = Database::sqlAnd($where);
			formatLog($sql_where , false);
		}
		
		switch($mode){
			case "head":
				$datas = $db->head($limit);
				break;
			case "tail":
				$datas = $db->tail($limit);
				break;
			case "all":
				$datas = $db->query(array("limit" => $limit, "where" => $sql_where));
				break;
		}
		
		echo "<p class='api'>" . json_encode($datas) . "</p>";
	}
?>