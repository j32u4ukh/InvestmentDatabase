<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/day_ohlc.php");
	
	function update($post){
		$stock_id = $post["stock_id"];
		$db = new DayOhlc();
		
		$params = array();
		
		// primary _keys = array("TIME");
		$params["TIME"] = $time;
		
		// array("OPEN", "HIGH", "LOW", "CLOSE", "VOL");		
		$params = updateParams($post, "OPEN", $params);
		$params = updateParams($post, "HIGH", $params);
		$params = updateParams($post, "LOW", $params);
		$params = updateParams($post, "CLOSE", $params);
		$params = updateParams($post, "VOL", $params);
		echo json_encode($params) . "<br>";
		
		$db->update($params);
		
		$where = array();
		$where[] = Database::sqlEq("TIME", "'" . $time . "'");		
		$sql_where = Database::sqlAnd($where);
		
		$datas = $db->query(array("where" => $sql_where));
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
		$stock_id = $post["stock_id"];
		$db = new DayOhlc($stock_id);
		
		$datas = json_decode($post["datas"], true);	
		
		$api_data = array("api_data"=>json_encode($datas));
		echo "<p class='api'>" . json_encode($api_data) . "</p>";
		
		$where = $db->updates($datas);		
		$datas = $db->query(array("where" => $where));
		
		$api_where = array("api_where"=>json_encode($where));
		echo "<p class='api'>" . json_encode($api_where) . "</p>";
		echo "<p class='api'>" . json_encode($datas) . "</p>";
	}	
?>