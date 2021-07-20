<?php
	include "database.php";
	
	class Capital extends Database{
		private $table = "CAPITAL";
		
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
								`NUMBER` INT NOT NULL AUTO_INCREMENT , 
								`TIME` VARCHAR(10) NOT NULL , 
								`USER` VARCHAR(10) NOT NULL , 
								`TYPE` VARCHAR(5) NOT NULL , 
								`FLOW` VARCHAR(10) NOT NULL , 
								`STOCK` VARCHAR(10) NOT NULL , 
								PRIMARY KEY (`NUMBER`)
								) ENGINE = InnoDB;";
			parent::getTable($this->table, $table_definition);
		}
	}
?>