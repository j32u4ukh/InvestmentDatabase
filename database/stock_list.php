<?php
	include_once "database/database.php";
	include_once "utils.php";
	
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
								 `NAME` VARCHAR(10) NOT NULL , 
								 `PRICE` VARCHAR(10) NOT NULL , 
								 PRIMARY KEY (`STOCK_ID`)";

			parent::getTable($this->table, $table_definition);
		}
		
		/*public function select($columns=array(), $n_limit=5){
			if(count($columns) == 0){
				$format_columns = "*";
			}else{
				// implode = join
				$format_columns = implode(",", $columns);
			}
			
			$this->sql = "SELECT $format_columns FROM $this->table LIMIT $n_limit";
			
			try {
				$result = $this->db->prepare($this->sql);
				$result->execute();
				
				return $result->fetchAll();
				
			} catch(PDOException $e) {
				$this->error_message = "<p class='bg-danger'> $e->getMessage() </p>";
			}
		}*/
	}
?>