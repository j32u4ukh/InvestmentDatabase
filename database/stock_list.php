<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	// TODO: sortStockWithPrice, selectByPriceRange, selectByStockIds, updateByStockId, isStockExists
	class StockList extends Database{
	    
		// 建構子從 access.txt 讀取連接資料庫所需資訊
		public function __construct(){
		    $this->table = "STOCK_LIST";
			$access = connectAccess();			
			parent::__construct($access["server"], $access["user"], $access["password"], $access["database"]);
			$this->getTable($this->table);
		}
		
		public function __destruct() {
			debugLog("Destroying $this->table");
			parent::__destruct();			
		}
		
		public function getTable($table=null, $table_definition=null){			
			$table_definition = "`STOCK_ID` VARCHAR(10) NOT NULL ,
								 `NAME` VARCHAR(10) , 
								 `PRICE` VARCHAR(10) NOT NULL , 
								 PRIMARY KEY (`STOCK_ID`)";
			$this->sql_columns = array("STOCK_ID", "NAME", "PRICE");
			$this->sort_by = "PRICE";
			$this->primary_keys = array("STOCK_ID");
			parent::getTable($this->table, $table_definition);
		}
	}
?>