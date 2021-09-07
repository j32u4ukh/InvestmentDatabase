<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/capital.php");
	
	function update($post){
		$params = array();
		
		// primary _keys = array("NUMBER");
		$number = $post["NUMBER"];
		$params["NUMBER"] = $number;
		
		// array("TIME", "USER", "TYPE", "FLOW", "STOCK", "REMARK");		
		$params = updateParams($post, "TIME", $params);
		$params = updateParams($post, "USER", $params);
		$params = updateParams($post, "TYPE", $params);
		$params = updateParams($post, "FLOW", $params);
		$params = updateParams($post, "STOCK", $params);
		$params = updateParams($post, "REMARK", $params);
		
		$db = new Capital(2);
		$db->update($params);
		
		$datas = $db->query(array("where" => Database::sqlEq("NUMBER", "'" . $number . "'")));
		echo "<p class='api'>" . json_encode($datas) . "</p>";
	}
	
	// 如果 map 裡面有 key，就將數值加入 params
	function updateParams($map, $key, $params){
		if(array_key_exists($key, $map)){
			$params[$key] = "'" . $map[$key] . "'";
		}
		
		return $params;
	}
	
	function updateMultiDatas($post){
		$datas = json_decode($post["datas"], true);
		
		$db = new Capital();
		$where = $db->updates($datas);
		
		$datas = $db->query(array("where" => $where));
		echo "<p class='api'>" . json_encode($datas) . "</p>";
	}
?>