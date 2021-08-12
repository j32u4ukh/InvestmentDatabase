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
			$this->sql_columns = array("GUID", "TIME", "STOCK_ID", "PRICE");
			$this->sort_by = "TIME";
			$this->primary_keys = array("GUID");
			parent::getTable($this->table, $table_definition);
		}
		
		public function update($data_array) {			
			$where_list = array();
			$setting_list = array();
			
			foreach ($data_array as $key => $value) {
				if(in_array($key, $this->primary_keys, true)){
					$where_list[] = "`$key` = '$value'";
				}else{
					$setting_list[] = "`$key` = $value";
				}
			}
			
			// implode = join
			$where = Database::sqlAnd($where_list);
			$setting = implode(",", $setting_list);
			$this->sql = "UPDATE $this->table SET $setting WHERE $where";
			$result = $this->db->prepare($this->sql);
			$result->execute();
		}
	}
?>