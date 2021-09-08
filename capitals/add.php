<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/capital.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	function add($post){
		$db = new Capital();
		
		$time = $post["TIME"];
		$user = $post["USER"];
		$type = $post["TYPE"];
		
		$flow = $post["FLOW"];
		$stock = $post["STOCK"];		
		$remark = $post["REMARK"];
							
		$datas = array(
			array("TIME"=>"$time", "USER"=>"$user", "TYPE"=>"$type", 
				  "FLOW"=>"$flow", "STOCK"=>"$stock", "REMARK"=>"$remark")
		);
		
		$db->insert($datas);
		$result = $db->tail(1);
						
		echo "<p>" . json_encode($result) . "</p>";
	}
	
	function addMultiDatas($post){
		$db = new Capital();
		
		$datas = json_decode($post["datas"], true);
		$n_data	= count($datas);	
		$db->insert($datas);		
		$result = $db->tail($n_data);
						
		echo "<p class='api'>" . json_encode($result) . "</p>";
	}
?>