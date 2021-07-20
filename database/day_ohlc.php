<?php
	include "database.php";
	
	class DayOhlc extends Database{
		private $table = "DAY_";
		
		// 建構子從 access.txt 讀取連接資料庫所需資訊
		public function __construct($stock_id){			
			$access = connectAccess();
			parent::__construct($access["server"], $access["user"], $access["password"], $access["database"]);
			$this->table .= $stock_id;
			$this->getTable($this->table);
		}
		
		public function __destruct() {
			print "Destroying $table\n";
			parent::__destruct();			
		}
		
		public function getTable(){			
			$table_definition = "CREATE TABLE `$this->database`.`$table` ( 
								`TIME` VARCHAR(10) NOT NULL , 
								`OPEN` VARCHAR(10) NOT NULL , 
								`HIGH` VARCHAR(10) NOT NULL , 
								`LOW` VARCHAR(10) NOT NULL , 
								`CLOSE` VARCHAR(10) NOT NULL , 
								`VOL` INT(10) NOT NULL , 
								PRIMARY KEY (`TIME`(10))
								) ENGINE = InnoDB";
			parent::getTable($this->table, $table_definition);
		}
	}
?>