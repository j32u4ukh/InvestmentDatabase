<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/trade_record.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	function add($stock_id, $buy_time, $sell_time, $buy_price, $sell_price, $vol, $buy_cost, $sell_cost, $revenue){
		$db = new TradeRecord();
		$datas = array(
			array("stock_id"=>"$stock_id", "buy_time"=>"$buy_time", "sell_time"=>"$sell_time",
				  "buy_price"=>"$buy_price", "sell_price"=>"$sell_price", "vol"=>"$vol",
				  "buy_cost"=>"$buy_cost", "sell_cost"=>"$sell_cost", "revenue"=>"$revenue")
		);
		
		$db->insert($datas);
		$data = $db->tail(1);
		$result = array("status"=>"success", 
						"data"=>$data);
						
		echo "<p>" . json_encode($result) . "</p>";
	}
?>