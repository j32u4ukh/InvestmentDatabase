<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/trade_record.php");
	
	function delete($post){
		$params = array();		
		$number = $post["NUMBER"];
		
		$db = new TradeRecord();
		$db->delete(array("NUMBER"=>$number));
		
		$where = Database::sqlEq("NUMBER", "'" . $number . "'");
		
		$datas = $db->query(array("where" => $where));
		echo "<p class='api'>" . json_encode($datas) . "</p>";
	}
?>