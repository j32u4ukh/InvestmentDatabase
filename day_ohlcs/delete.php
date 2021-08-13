<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/day_ohlc.php");
	
	function delete($post){	
		$stock_id = $post["stock_id"];		
		$db = new DayOhlc($stock_id);
		
		$time = $post["TIME"];		
		$db->delete(array("TIME"=>$time));		
		$where = Database::sqlEq("TIME", "'" . $time . "'");		
		$datas = $db->query(array("where" => $where));
		
		echo "<p class='api'>" . json_encode($datas) . "</p>";
	}
?>