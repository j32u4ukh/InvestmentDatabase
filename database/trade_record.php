<?php
	include_once "database/database.php";
	include_once "utils.php";
	
	class TradeRecord extends Database{
		
		// 建構子從 access.txt 讀取連接資料庫所需資訊
		public function __construct(){
			$this->table = "TRADE_RECORD";
			$access = connectAccess();
			parent::__construct($access["server"], $access["user"], $access["password"], $access["database"]);
			$this->getTable($this->table);
		}
		
		public function __destruct() {
			debugLog("Destroying $this->table");
			parent::__destruct();			
		}
		
		public function getTable($table=null, $table_definition=null){	
			$table_definition = "`NUMBER` INT NOT NULL AUTO_INCREMENT ,
								 `STOCK_ID` VARCHAR(10) NOT NULL ,
								 `BUY_TIME` VARCHAR(10) NOT NULL , 
								 `SELL_TIME` VARCHAR(10) NOT NULL ,
								 `BUY_PRICE` VARCHAR(10) NOT NULL ,
								 `SELL_PRICE` VARCHAR(10) NOT NULL , 
								 `VOL` VARCHAR(10) NOT NULL ,
								 `BUY_COST` VARCHAR(10) NOT NULL ,
								 `SELL_COST` VARCHAR(10) NOT NULL , 
								 `REVENUE` VARCHAR(10) NOT NULL , 
								 PRIMARY KEY (`NUMBER`)";
			parent::getTable($this->table, $table_definition);
		}
	}
?>