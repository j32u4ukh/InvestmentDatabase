<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/stock_list.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	function add($post){
		$db = new StockList();
		
		$stock_id = $post["stock_id"];
		$name = defaultMapValue($get, "name", "");
		$price = $post["price"];
							
		$datas = array(
			array("STOCK_ID"=>"$stock_id", "NAME"=>"$name", "PRICE"=>"$price")
		);
		
		$db->insert($datas);
		$data = $db->tail(1);
		$result = array("status"=>"success", 
						"data"=>$data);
						
		echo "<p class='api'>" . json_encode($result) . "</p>";
	}
	
	function addMultiDatas($post){
		$db = new StockList();
		
		$datas = json_decode($post["datas"], true);
		$db->insert($datas);
		
		$where = array();
		
		foreach($datas as $data){
			$where[] = Database::sqlEq("STOCK_ID", "'" . $data["STOCK_ID"] . "'");
		}

		$sql_where = Database::sqlOr($where);
		$result = $db->query(array("where" => $sql_where));
						
		echo "<p class='api'>" . json_encode($result) . "</p>";
	}
?>