<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/day_ohlc.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	function read($get){
		$stock_id = $get["stock_id"];	
		$db = new DayOhlc($stock_id);
		
		$mode = $get["mode"];		
		$limit = defaultMapValue($get, "limit", 5);
		
		$where = array();
		
		if(array_key_exists("start_time", $get)){
			$where[] = Database::sqlGe("TIME", $get["start_time"]);
		}
		
		if(array_key_exists("end_time", $get)){
			$where[] = Database::sqlLe("TIME", $get["end_time"]);
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
				$datas = $db->read(array("where" => $sql_where));
				break;
		}
		
		echo "<p class='api'>" . json_encode($datas) . "</p>";
	}
?>