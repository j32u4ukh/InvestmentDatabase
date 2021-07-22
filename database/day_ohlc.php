<?php
	include_once "database/database.php";
	include_once "utils.php";
	
	class DayOhlc extends Database{
		
		// 建構子從 access.txt 讀取連接資料庫所需資訊
		public function __construct($stock_id){	
			$this->table = "DAY_" . $stock_id;
			$access = connectAccess();
			parent::__construct($access["server"], $access["user"], $access["password"], $access["database"]);
			$this->getTable($this->table);
		}
		
		public function __destruct() {
			debugLog("Destroying $this->table");
			parent::__destruct();			
		}
		
		public function getTable($table=null, $table_definition=null){	
			$table_definition = "`TIME` VARCHAR(10) NOT NULL ,
								 `OPEN` VARCHAR(10) NOT NULL , 
								 `HIGH` VARCHAR(10) NOT NULL ,
								 `LOW` VARCHAR(10) NOT NULL , 
								 `CLOSE` VARCHAR(10) NOT NULL , 
								 `VOL` INT(10) NOT NULL , 
								 PRIMARY KEY (`TIME`(10))";
			parent::getTable($this->table, $table_definition);
		}
	}
?>