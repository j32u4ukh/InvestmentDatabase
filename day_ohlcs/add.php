<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/day_ohlc.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	function add($post){
		$stock_id = $post["stock_id"];
		$db = new DayOhlc($stock_id);

		$time = $post["TIME"];
		
		$open_price = $post["open"];
		$high_price = $post["high"];
		$low_price = $post["low"];
		$close_price = $post["close"];
		
		$volumn = $post["vol"];
							
		$datas = array(
			array("TIME"=>"$time", 
				  "OPEN"=>"$open_price", 
				  "HIGH"=>"$high_price", 
				  "LOW"=>"$low_price", 
				  "CLOSE"=>"$close_price", 
				  "VOL"=>"$volumn")
		);
		
		$db->insert($datas);
		$data = $db->tail(1);
		$result = array("status"=>"success", 
						"data"=>$data);
						
		echo "<p class='api'>" . json_encode($result) . "</p>";
	}
	
	function addMultiDatas($post){
		$stock_id = $post["stock_id"];
		$db = new DayOhlc($stock_id);
		
		$datas = json_decode($post["datas"], true);
		$n_data = count($datas);
		$db->insert($datas);

		$result = $db->tail($n_data);
						
		echo "<p class='api'>" . json_encode($result) . "</p>";
	}
?>