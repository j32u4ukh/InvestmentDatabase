<?php
	include "database.php";
	
	class Inventory extends Database{
		private $table = "INVENTORY";
		
		// 建構子從 access.txt 讀取連接資料庫所需資訊
		public function __construct(){
			$access = connectAccess();
			parent::__construct($access["server"], $access["user"], $access["password"], $access["database"]);
			$this->getTable($this->table);
		}
		
		public function __destruct() {
			print "Destroying $table\n";
			parent::__destruct();			
		}
		
		public function getTable(){			
			$table_definition = "CREATE TABLE `$this->database`.`$table` ( 
								`GUID` VARCHAR(32) NOT NULL , 
								`TIME` VARCHAR(10) NOT NULL , 
								`STOCK_ID` VARCHAR(10) NOT NULL , 
								`PRICE` VARCHAR(10) NOT NULL , 
								PRIMARY KEY (`GUID`)
) ENGINE = InnoDB;";
			parent::getTable($this->table, $table_definition);
		}
	}
?>