<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	class Capital extends Database{
		
		// 建構子從 access.txt 讀取連接資料庫所需資訊
		public function __construct($version = 1){
			$this->table = "CAPITAL$version";
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
								 `TYPE` VARCHAR(10) NOT NULL , 
								 `FLOW` VARCHAR(10) NOT NULL ,
								 `STOCK` VARCHAR(10) NOT NULL , 
								 `REMARK` VARCHAR(50) NOT NULL , 
								 PRIMARY KEY (`NUMBER`)";
								 
			$this->sql_columns = array("NUMBER", "TIME", "USER", "TYPE", "FLOW", "STOCK", "REMARK");

			$this->sort_by = "TIME";
			$this->primary_keys = array("NUMBER");
			parent::getTable($this->table, $table_definition);
		}
		
		public function insertValues($data, $index){
			// Capital 的第一個欄位為 auto increment，無須自己給值就會自動遞增，指令上要傳入 NULL
			$temp = array("NULL");
		
			foreach($data as $key => $value){
				$temp[] = ":$key" . "_" . $index;
			}
			
			// implode = join
			$values = implode(",", $temp);
			
			// (NULL, :STOCK_ID_i,:BUY_TIME_i,:SELL_TIME_i,:BUY_PRICE_i,:SELL_PRICE_i,:VOL_i,:BUY_COST_i,:SELL_COST_i,:REVENUE_i)
			$values_prepare = "($values)";
			
			return $values_prepare;
		}
		
		public function insert($datas, $table=null){
			$datas = $this->insertFilter($datas);
			
			parent::insert($datas);
		}
	}
?>