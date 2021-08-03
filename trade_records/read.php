<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/trade_record.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	function read($read, $limit=null, $start_buy=null, $end_buy=null, $start_sell=null, $end_sell=null){
		$db = new TradeRecord();
		$limit = defaultValue($limit, 5);
		
		$where = array();
		
		if(isset($start_buy)){
			$where[] = Database::sqlGe("BUY_TIME", $start_buy);
		}
		
		if(isset($end_buy)){
			$where[] = Database::sqlLe("BUY_TIME", $end_buy);
		}
		
		if(isset($start_sell)){
			$where[] = Database::sqlGe("SELL_TIME", $start_sell);
		}
		
		if(isset($end_sell)){
			$where[] = Database::sqlLe("SELL_TIME", $end_sell);
		}
		
		$sql_where = null;
		
		if(count($where) == 0){
			$sql_where = Database::sqlAnd($where);
			formatLog($sql_where , false);
		}
		
		switch($read){
			case "head":
				$datas = $db->head($limit);
				break;
			case "tail":
				$datas = $db->tail($limit);
				break;
			case "all":
				$datas = $this->query(array("limit" => $limit, "where" => $sql_where));
				break;
		}
		
		echo "<p>" . json_encode($datas) . "</p>";
	}
?>