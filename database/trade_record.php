<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/database.php");
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
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
			$table_definition = "`NUMBER` VARCHAR(10) NOT NULL , 
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
			$this->sql_columns = array("NUMBER", "STOCK_ID", "BUY_TIME", "SELL_TIME", "BUY_PRICE", "SELL_PRICE", "VOL", 
									   "BUY_COST", "SELL_COST", "REVENUE");
			$this->sort_by = "NUMBER";
			$this->primary_keys = array("NUMBER");
			
			parent::getTable($this->table, $table_definition);
		}
		
		// [TradeRecord] 為 INSERT 準備 Value 要插入的空格
		// public function insertValues($data, $index){
			// TradeRecord 的第一個欄位為 auto increment，無須自己給值就會自動遞增，指令上要傳入 NULL
			// $temp = array("NULL");
		
			// foreach($data as $key => $value){
				// $temp[] = ":$key" . "_" . $index;
			// }
			
			// implode = join
			// $values = implode(",", $temp);
			
			// (NULL, :STOCK_ID_i,:BUY_TIME_i,:SELL_TIME_i,:BUY_PRICE_i,:SELL_PRICE_i,:VOL_i,:BUY_COST_i,:SELL_COST_i,:REVENUE_i)
			// $values_prepare = "($values)";
			
			// return $values_prepare;
		// }
	}
?>