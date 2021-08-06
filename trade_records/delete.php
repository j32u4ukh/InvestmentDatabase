<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/trade_record.php");
	
	function delete($post){
		$params = array();
		
		// primary _keys = array("STOCK_ID", "BUY_TIME", "SELL_TIME");
		$stock_id = $post["STOCK_ID"];
		$buy_time = $post["BUY_TIME"];
		$sell_time = $post["SELL_TIME"];
		
		$params["STOCK_ID"] = $stock_id;
		$params["BUY_TIME"] = $buy_time;
		$params["SELL_TIME"] = $sell_time;
		
		$db = new TradeRecord();
		$db->delete($params);
		
		$where = array();
		$where[] = Database::sqlEq("STOCK_ID", "'" . $stock_id . "'");
		$where[] = Database::sqlEq("BUY_TIME", "'" . $buy_time . "'");
		$where[] = Database::sqlEq("SELL_TIME", "'" . $sell_time . "'");
		
		// 返回部分條件相同的數據
		$sql_where = Database::sqlOr($where);
		
		$datas = $db->query(array("where" => $sql_where));
		echo "<p class='api'>" . json_encode($datas) . "</p>";
	}
?>