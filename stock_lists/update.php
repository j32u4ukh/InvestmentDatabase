<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/stock_list.php");
	
	function update($post){
		$params = array();
		
		// primary _keys = array("STOCK_ID");
		$stock_id = $post["STOCK_ID"];
		$params["STOCK_ID"] = $stock_id;
		
		// array("NAME", "PRICE");		
		$params = updateParams($post, "NAME", $params);
		$params = updateParams($post, "PRICE", $params);
		echo json_encode($params) . "<br>";
		
		$db = new StockList();
		$db->update($params);
		
		$where = array();
		$where[] = Database::sqlEq("STOCK_ID", "'" . $stock_id . "'");		
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
		$datas = json_decode($post["datas"], true);
		
		$db = new StockList();
		$where = $db->updates($datas);		
		$datas = $db->query(array("where" => $where));
		
		echo "<p class='api'>" . json_encode($datas) . "</p>";
	}
	
	function testOnUpdate(){
		echo "<form action='./index.php' method='post'>";
		echo "	rest:<input type='text' id='rest' name='rest'><br>";
		echo "	mode:<input type='text' id='mode' name='mode'><br>";
		echo "  guid:<input type='text' id='GUID' name='GUID'><br>";
		// echo "  time:<input type='text' id='time' name='time'><br>";
		// echo "  stock_id:<input type='text' id='stock_id' name='stock_id'><br>";		
		echo "	price:<input type='text' id='PRICE' name='PRICE'><br>";
		echo "	<input type='submit' value='Submit'>";
		echo "</form>";
	}
?>