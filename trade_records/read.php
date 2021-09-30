<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/trade_record.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	function read($get){
		$db = new TradeRecord();
		$mode = $get["mode"];
		
		if($mode == "number"){
			$number = $db->getNumber();
			echo "<p class='api'>$number</p>";
			return;
		}
		
		$limit = defaultMapValue($get, "limit", 5);
		
		$where = array();
		
		if(array_key_exists("start_buy", $get)){
			$where[] = Database::sqlGe("BUY_TIME", $get["start_buy"]);
		}
		
		if(array_key_exists("end_buy", $get)){
			$where[] = Database::sqlLe("BUY_TIME", $get["end_buy"]);
		}
		
		if(array_key_exists("start_sell", $get)){
			$where[] = Database::sqlGe("SELL_TIME", $get["start_sell"]);
		}
		
		if(array_key_exists("end_sell", $get)){
			$where[] = Database::sqlLe("SELL_TIME", $get["end_sell"]);
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