<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/inventory.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	function read($get){
		$db = new Inventory();
		$mode = $get["mode"];		
		$limit = defaultMapValue($get, "limit", 5);
		
		$where = array();
		
		if(array_key_exists("start_buy", $get)){
			$where[] = Database::sqlGe("BUY_TIME", $get["start_buy"]);
		}
		
		if(array_key_exists("end_buy", $get)){
			$where[] = Database::sqlLe("BUY_TIME", $get["end_buy"]);
		}
		
		if(array_key_exists("stock_id", $get)){
			$stock_ids = explode(',', $get["stock_id"]);
			$stock_list = array();
			
			foreach($stock_ids as $stock_id){
				$stock_list[] = Database::sqlEq("STOCK_ID", $stock_id);
			}
			
			$where[] = "(" . Database::sqlOr($stock_list) . ")";
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
				$datas = $db->query(array("where" => $sql_where));
				break;
		}
		
		echo "<p class='api'>" . json_encode($datas) . "</p>";
	}
?>