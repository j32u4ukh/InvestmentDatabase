<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	class Capital extends Database{
		
		// 建構子從 access.txt 讀取連接資料庫所需資訊
		public function __construct(){
			$this->table = "CAPITAL";
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
								 `TIME` VARCHAR(10) NOT NULL ,
								 `USER` VARCHAR(10) NOT NULL , 
								 `TYPE` VARCHAR(5) NOT NULL , 
								 `FLOW` VARCHAR(10) NOT NULL ,
								 `STOCK` VARCHAR(10) NOT NULL , 
								 PRIMARY KEY (`NUMBER`)";
			parent::getTable($this->table, $table_definition);
		}
	}
?>