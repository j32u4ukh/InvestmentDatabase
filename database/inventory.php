<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	class Inventory extends Database{
		
		// 建構子從 access.txt 讀取連接資料庫所需資訊
		public function __construct(){
			$this->table = "INVENTORY";
			$access = connectAccess();
			parent::__construct($access["server"], $access["user"], $access["password"], $access["database"]);
			$this->getTable($this->table);
		}
		
		public function __destruct() {
			debugLog("Destroying $this->table");
			parent::__destruct();			
		}
		
		public function getTable($table=null, $table_definition=null){	
			$table_definition = "`GUID` VARCHAR(32) NOT NULL ,
								 `TIME` VARCHAR(10) NOT NULL , 
								 `STOCK_ID` VARCHAR(10) NOT NULL , 
								 `PRICE` VARCHAR(10) NOT NULL , 
								 PRIMARY KEY (`GUID`)";
			parent::getTable($this->table, $table_definition);
		}
	}
?>