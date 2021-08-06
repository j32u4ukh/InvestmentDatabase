<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/trade_record.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	function add($post){
		$db = new TradeRecord();
		
		$number = $post["number"];
		$stock_id = $post["stock_id"];
		$buy_time = $post["buy_time"];
		$sell_time = $post["sell_time"];
		
		$buy_price = $post["buy_price"];
		$sell_price = $post["sell_price"];		
		$vol = $post["vol"];		
		$buy_cost = $post["buy_cost"];
		$sell_cost = $post["sell_cost"];
		$revenue = $post["revenue"];
							
		$datas = array(
			array("NUMBER"=>"$number", "STOCK_ID"=>"$stock_id", "BUY_TIME"=>"$buy_time", "SELL_TIME"=>"$sell_time", 
				  "BUY_PRICE"=>"$buy_price", "SELL_PRICE"=>"$sell_price", "VOL"=>"$vol",
				  "BUY_COST"=>"$buy_cost", "SELL_COST"=>"$sell_cost", "REVENUE"=>"$revenue")
		);
		
		$db->insert($datas);
		$data = $db->tail(1);
		$result = array("status"=>"success", 
						"data"=>$data);
						
		echo "<p>" . json_encode($result) . "</p>";
	}
	
	function addMultiDatas($post){
		$db = new TradeRecord();
		
		$datas = json_decode($post["datas"], true);
		$n_data	= count($datas);	
		$db->insert($datas);
		
		$datas = $db->tail($n_data);
		$result = array("status"=>"success", 
						"datas"=>$datas);
						
		echo "<p class='api'>" . json_encode($result) . "</p>";
	}
	
	function testOnAdd(){
		echo "<form action='./index.php' method='post'>";
		echo "	mode:<input type='text' id='mode' name='mode'><br>";
		echo "  stock_id:<input type='text' id='stock_id' name='stock_id'><br>";
		echo "  buy_time:<input type='text' id='buy_time' name='buy_time'><br>";
		echo "  sell_time:<input type='text' id='sell_time' name='sell_time'><br>";

		echo "	buy_price:<input type='text' id='buy_price' name='buy_price'><br>";
		echo "	sell_price:<input type='text' id='sell_price' name='sell_price'><br>";
		echo "	vol:<input type='text' id='vol' name='vol'><br>";
		echo "	buy_cost:<input type='text' id='buy_cost' name='buy_cost'><br>";
		echo "	sell_cost:<input type='text' id='sell_cost' name='sell_cost'><br>";
		echo "	revenue:<input type='text' id='revenue' name='revenue'><br>";
		echo "	<input type='submit' value='Submit'>";
		echo "<</form>-->";
	}
?>