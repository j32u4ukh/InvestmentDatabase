<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/inventory.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	function add($post){
		$db = new Inventory();
		
		$guid = $post["guid"];
		$time = $post["time"];
		$stock_id = $post["stock_id"];
		$price = $post["price"];
							
		$datas = array(
			array("GUID"=>"$guid", "TIME"=>"$time", "STOCK_ID"=>"$stock_id", "PRICE"=>"$price")
		);
		
		$db->insert($datas);
		$data = $db->tail(1);
		$result = array("status"=>"success", 
						"data"=>$data);
						
		echo "<p class='api'>" . json_encode($result) . "</p>";
	}
	
	function addMultiDatas($post){
		$db = new Inventory();
		
		$datas = json_decode($post["datas"], true);
		$n_data	= count($datas);	
		$db->insert($datas);		
		$result = $db->tail($n_data);
						
		echo "<p class='api'>" . json_encode($result) . "</p>";
	}
?>