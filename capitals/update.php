<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/trade_record.php");
	
	function renumber(){
		$db = new TradeRecord();
		// TODO: 取得最大 NUMBER
		$datas = $db->read();
		$max_number = 0;
				
		foreach($datas as $data)
		{
			foreach($data as $key => $value)
			{
				if($key == "NUMBER"){
					$max_number = max($max_number, $value);
				}
			}
		}
		
		echo "max_number: $max_number<br>";
		
		// 以最大值更新一輪 NUMBER 再重新由 1 開始編碼，避免 NUMBER 重複 
		$db->renumber();
		echo "Renumber processing...<br>";
		
		$db->renumber();
	}
	
	function update($post){
		$params = array();
		
		// primary _keys = array("STOCK_ID", "BUY_TIME", "SELL_TIME");
		$number = $post["NUMBER"];
		$stock_id = $post["STOCK_ID"];
		$buy_time = $post["BUY_TIME"];
		$sell_time = $post["SELL_TIME"];
		
		$params["STOCK_ID"] = $stock_id;
		$params["BUY_TIME"] = $buy_time;
		$params["SELL_TIME"] = $sell_time;
		$params["NUMBER"] = $number;
		
		// array("BUY_PRICE", "SELL_PRICE", "VOL", "BUY_COST", "SELL_COST", "REVENUE");		
		$params = updateParams($post, "BUY_PRICE", $params);
		$params = updateParams($post, "SELL_PRICE", $params);
		$params = updateParams($post, "VOL", $params);
		$params = updateParams($post, "BUY_COST", $params);
		$params = updateParams($post, "SELL_COST", $params);
		$params = updateParams($post, "REVENUE", $params);
		
		$db = new TradeRecord();
		$db->update($params);
		
		$where = array();
		$where[] = Database::sqlEq("STOCK_ID", "'" . $stock_id . "'");
		$where[] = Database::sqlEq("BUY_TIME", "'" . $buy_time . "'");
		$where[] = Database::sqlEq("SELL_TIME", "'" . $sell_time . "'");		
		$where[] = Database::sqlEq("NUMBER", "'" . $number . "'");		
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
		
		$db = new TradeRecord();
		$where = $db->updates($datas);
		
		$datas = $db->query(array("where" => $where));
		echo "<p class='api'>" . json_encode($datas) . "</p>";
	}
?>