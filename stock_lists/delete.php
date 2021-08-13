<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/stock_list.php");
	
	function delete($post){
		$params = array();		
		$stock_id = $post["STOCK_ID"];
		
		$db = new StockList();
		$db->delete(array("STOCK_ID"=>$stock_id));		
		$where = Database::sqlEq("STOCK_ID", "'" . $stock_id . "'");		
		$datas = $db->query(array("where" => $where));
		
		echo "<p class='api'>" . json_encode($datas) . "</p>";
	}
?>