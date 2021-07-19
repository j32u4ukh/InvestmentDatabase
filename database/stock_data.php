<?php
	function connectToStockData(){
		$server = "localhost";
		$user = "id17236763_j32u4ukh";
		$password = ">_AJeTJOD#bH_l2(";
		$database = "id17236763_stock_data";		
		$database = new StockData("localhost", "id17236763_j32u4ukh", ">_AJeTJOD#bH_l2(", "id17236763_stock_data");
		
		return $database;
	}
	
	class StockData{
		
	}
?>