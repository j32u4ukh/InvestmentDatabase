<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/inventory.php");
	
	function delete($post){
		$params = array();		
		$guid = $post["GUID"];
		
		$db = new Inventory();
		$db->delete(array("GUID"=>$guid));		
		$where = Database::sqlEq("GUID", "'" . $guid . "'");		
		$datas = $db->query(array("where" => $where));
		
		echo "<p class='api'>" . json_encode($datas) . "</p>";
	}
?>