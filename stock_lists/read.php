<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/stock_list.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	function read($get){
		$db = new StockList();
		$mode = $get["mode"];		
		$limit = defaultMapValue($get, "limit", 5);
		
		$where = array();
		
		if(array_key_exists("min", $get)){
			$where[] = Database::sqlGe("PRICE", $get["min"]);
		}
		
		if(array_key_exists("max", $get)){
			$where[] = Database::sqlLe("PRICE", $get["max"]);
		}
		
		$sql_where = null;
		
		// True -> 正序; False -> 逆序
		$positive_order = defaultMapValue($get, "sort", "1");
		
		if($positive_order == "1"){
			$sort = "ASC";
		}else{
			$sort = "DESC";
		}
		
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
				$datas = $db->read(array("where" => $sql_where, "sort_type" => $sort));
				break;
		}
		
		echo "<p class='api'>" . json_encode($datas) . "</p>";
	}
?>