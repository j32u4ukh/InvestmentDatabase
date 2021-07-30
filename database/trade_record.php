<?php
	include_once "database/database.php";
	include_once "utils.php";
	
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
			$table_definition = "`NUMBER` INT NOT NULL AUTO_INCREMENT ,
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
			parent::getTable($this->table, $table_definition);
		}
		
		/*INSERT INTO `TRADE_RECORD` 
		(`NUMBER`, `STOCK_ID`, `BUY_TIME`, `SELL_TIME`, `BUY_PRICE`, `SELL_PRICE`, `VOL`, `BUY_COST`, `SELL_COST`, `REVENUE`) 
		VALUES (NULL, '1712','2021-03-29','2021-04-14','22.6','21.75','1','22620','85','-955');*/
		
		public function valueFromRawData($raw_data){
			// raw_data: 3048,2021-02-26,2021-03-16,24.05,32,1,24070,116,7814
			
		}
		
		public function valueFromArray($data_array){
			
		}
		
		// [TradeRecord] 為 INSERT 準備 Value 要插入的空格
		public function insertValues($data, $index){
			// TradeRecord 的第一個欄位為 auto increment，無須自己給值就會自動遞增，指令上要傳入 NULL
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
		
		public function insert($datas){
			// $datas = array(
				// array(	"STOCK_ID"=>"2012", "BUY_TIME"=>"2021-03-23", "SELL_TIME"=>"2021-04-23", 
						// "BUY_PRICE"=>"19.95", "SELL_PRICE"=>"25.10", "VOL"=>"1", 
						// "BUY_COST"=>"19970.00", "SELL_COST"=>"95", "REVENUE"=>"5232.00"),
				// array(	"STOCK_ID"=>"2012", "BUY_TIME"=>"2021-03-24", "SELL_TIME"=>"2021-04-21", 
						// "BUY_PRICE"=>"0", "SELL_PRICE"=>"0", "VOL"=>"0", 
						// "BUY_COST"=>"0", "SELL_COST"=>"0", "REVENUE"=>"590"),
				
			// );
			
			$n_data = count($datas);
			$values_array = array();
			$data_array = array();
			
			for ($i = 0; $i < $n_data; $i++) {
			  $data = $datas[$i];
			  
			  // 即將添加的欄位名稱
			  $values_array[] = $this->insertValues($data, $i);
			  
			  // 準備添加的實際數據
			  $data_array = $this->insertDatas($data_array, $data, $i);
			}
			
			$values = implode(",", $values_array);
			formatLog("values: $values");
			print json_encode($data_array);
							
			$this->sql = "INSERT INTO `TRADE_RECORD` 
			(`NUMBER`, `STOCK_ID`, `BUY_TIME`, `SELL_TIME`, `BUY_PRICE`, `SELL_PRICE`, `VOL`, `BUY_COST`, `SELL_COST`, `REVENUE`)
			VALUES $values;";
			formatLog("sql: $this->sql");
			
			$result = $this->db->prepare($this->sql);
			$result->execute($datas);
			
			$this->last_id = $this->db->lastInsertId();
			formatLog("last_id: $this->last_id");
		}
	}
?>