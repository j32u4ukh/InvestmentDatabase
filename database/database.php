<?php
	include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
	
	function connectAccess(){
		$keys = array("server", "user", "password", "database");
		$file = fopen($_SERVER['DOCUMENT_ROOT'] . "/database/access.txt", "r");	
		$access = array();			
		$i = 0;
		
		//輸出文字中所有的行，直到檔案結束為止。
		while(! feof($file))
		{
			$access[$keys[$i]] = trim(fgets($file));
			$i++;
		}
		
		fclose($file);
		
		return $access;
	}
	
	// 參考：https://ithelp.ithome.com.tw/articles/10195426
	// TODO: getDatabaseInfo, getAllTableName, isTableExists, isTableEmpty, getTableInfo, isDataExists
	// TODO: ResourceData | getLastTime, getDataInfo, getHistoryData, getLastTimeCore, getTimeSection, selectTimeFliter
	class Database{
		// 將用於再次連線
		private $server = null;
		private $user = null;
		private $password = null;
		
		protected $database = null;
		protected $db = null;
		protected $table = null;
		protected $sql_columns = null;
		protected $update_keys = null;
		protected $sort_by = null;
		
		protected $sql = "";
		protected $last_id = 0;
		protected $last_num_rows = 0;
		protected $error_message = "";
		
		// 『建構式』會在物件被 new 時自動執行
		public function __construct($server, $user, $password, $database){
			$this->database = $database;
			$this->connectPDO($server, $user, $password, $database);
		}
		
		public function __destruct() {
			$this->db = NULL;
		}
		
		public static function sqlAnd($querys = array()){
			return implode(" AND ", $querys);
		}
		
		public static function sqlOr($querys = array()){
			return implode(" OR ", $querys);
		}
		
		// key > value
		public static function sqlGt($key, $value){
			return "$key > $value";
		}
		
		// key >= value
		public static function sqlGe($key, $value){
			return "$key >= $value";
		}
		
		// key = value
		public static function sqlEq($key, $value){
			return "$key = $value";
		}
		
		// key != value
		public static function sqlNe($key, $value){
			return "$key != $value";
		}
		
		// key <= value
		public static function sqlLe($key, $value){
			return "$key <= $value";
		}
		
		// key < value
		public static function sqlLt($key, $value){
			return "$key < $value";
		}
		
		// 建立跟資料庫的連接，並設定語系是萬國語言以支援中文
		protected function connectPDO($server, $user, $password, $database){
			// 將用於再次連線
			$this->server = $server;
			$this->user = $user;
			$this->password = $password;
			
			try {
				$this->db = new PDO("mysql:host=$server; charset=utf8mb4; dbname=$database", $user, $password);

				// set the PDO error mode to exception
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				formatLog("Connected successfully!!");
			}
			catch(PDOException $e) {
				formatLog("Connection failed: " . $e->getMessage());
			}
		}
		
		public function getTable($table, $table_definition){
			/* 若該表格不存在，則建立表格
			
			:param table_name: 表格名稱
			:param table_definition: 表格定義
			:return: 
			*/
			$this->sql = "CREATE TABLE IF NOT EXISTS `$this->database`.`$table` ({$table_definition}) ENGINE = InnoDB;";
			formatLog("sql: $this->sql");
			$this->execute($this->sql);
		}
		
		public function execute($sql, $data_array=array()) {
			try {
				$this->sql = $sql;
				$result = $this->db->prepare($sql);
				
				if(count($data_array) == 0){
					$result->execute();
				}else{
					$result->execute($data_array);
				}
				
				return $result->fetchAll(); 
				
			} catch(PDOException $e) {
				// 嘗試重新連線一次
				$this->connectPDO($this->server, $this->user, $this->password, $this->database);
				formatLog("嘗試重新連線一次");
				
				// 再次執行 sql 指令
				try {
					$this->sql = $sql;
					$result = $this->db->prepare($sql);
					
					if(count($data_array) == 0){
						$result->execute();
					}else{
						$result->execute($data_array);
					}
					
					return $result->fetchAll(); 
					
				} catch(PDOException $e) {
					$this->error_message = "<p class='bg-danger'>" . $e->getMessage() . "</p>";
					// echo $this->error_message;
				}
			}
		}
		
		public function formatColumns($columns=null){
			if(is_null($columns)){
				return "*";
			}
			elseif(count($columns) == 1){
				// ['*'] 也會被歸到這裡
				return $columns[0];
			}
			else{
				return implode(",", $columns);
			}
		}
		
		// TODO: 檢查資料表是否存在 https://stackoverflow.com/questions/1717495/check-if-a-database-table-exists-using-php-pdo
		
		// // 為 INSERT 準備 sql 指令與欄位
		// public function insertColumns($table, $data){
			// $temp = array();
		
			// foreach($data as $key => $value){
				// $temp[] = $key;
			// }
			
			// // implode = join			
			// return implode(",", $temp);
		// }
		
		// 為 INSERT 準備 Value 要插入的空格
		public function insertValues($data, $index){
			// (:house_id_{$n},:room_name_{$n},:monthly_rental_amount_{$n},:security_deposit_amount_{$n},:room_floor_{$n})
			$temp = array();
		
			foreach($data as $key => $value){
				$temp[] = ":$key" . "_" . $index;
			}
			
			// implode = join
			$values = implode(",", $temp);
			$values_prepare = "($values)";
			
			return $values_prepare;
		}
		
		// 為 INSERT 準備要插入的 Value 實際內容
		public function insertDatas($data_array, $data, $index){
			$temp = array();
		
			foreach($data as $key => $value){
				// $dataAdd[':house_id_'.$n] = $_d['house_id'];
				$data_array[":$key" . "_" . $index] = $value;
			}
			
			return $data_array;
		}
		
		// 呼叫一次，可插入一到多筆數據
		public function insert($datas, $table=null) {
			$columns = implode(",", $this->sql_columns);
			
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
			
			// INSERT IGNORE INTO table_name (KEY1, KEY2) VALUES (:KEY1, :KEY2), (:KEY1, :KEY2), (:KEY1, :KEY2);
			$this->sql = "INSERT IGNORE INTO `$this->table` ($columns) VALUES $values;";
			formatLog("sql: $this->sql");
			
			$result = $this->db->prepare($this->sql);
			$result->execute($data_array);
			
			$this->last_id = $this->db->lastInsertId();
		}

		// 讀取資料表
		public function select($table=null, $columns=null, $where=null, $sort_by=null, $sort_type="ASC", 
							   $limit=null, $offset=0){
			/*將 table_name 結果按 column_name [ 升序 | 降序 ] 排序
			SELECT *
			FROM table_name
			TODO: WHERE [ conditions1 AND conditions2 ]
			ORDER BY column_name [ASC | DESC];

			:param table_name: 表格名稱
			:param columns: 欄位名稱
			:param where: 篩選條件
			:param sort_by: 排須依據哪些欄位
			:param sort_type: 升序(ASC) | 降序(DESC)
			:param limit: 限制從表格中提取的行數
			:param offset: 從第幾筆數據開始呈現(從 0 開始數)
			:return:
			*/			
			$format_columns = $this->formatColumns($columns);
			
			if(is_null($where)){
				$where = "";
			}else{
				$where = "WHERE $where";
			}
			
			if(is_null($sort_by)){
				$sort = "";
			}else{
				$sort = "ORDER BY $sort_by $sort_type";
			}
			
			// LIMIT & OFFSET 似乎必須一起使用
			if(is_null($limit)){
				$limit_offset = "";
			}else{
				$limit_offset = "LIMIT $limit OFFSET $offset";
			}
			
			$this->sql = "SELECT $format_columns FROM $this->table $where $sort $limit_offset;";
			formatLog("sql: $this->sql");
			
			try {
				$result = $this->db->prepare($this->sql);
				// $result->execute($data_array);
				$result->execute();
				
				return $result->fetchAll();
				
			} catch(PDOException $e) {
				formatLog("Error: " . $e->getMessage());
			}
		}
		
		// 高階 select 傳入參數為 array，使得參數使用更為彈性
		public function query($params = array()){
			$columns = defaultMapValue($params, "colums", null);
			$where = defaultMapValue($params, "where", null);
			$sort_by = defaultMapValue($params, "sort_by", $this->sort_by);
			$sort_type = defaultMapValue($params, "sort_type", "ASC");
			$limit = defaultMapValue($params, "limit", null);
			$offset = defaultMapValue($params, "offset", 0);

			$results = $this->select(null, $columns, $where, $sort_by, $sort_type, $limit, $offset);			
			$datas = array();
			
			foreach($results as $result){
				$data = array();
				
				// 將欄位名稱對應的數據加入 $data
				foreach($result as $key => $value){
					if(in_array($key, $this->sql_columns, true)){
						$data[$key] = $value;
					}
				}
				
				// 將每一筆 $data 加入 $datas
				$datas[] = $data;
			}
			
			return $datas;
		}
		
		public function head($limit=5){			
			$datas = $this->query(array("limit" => $limit, "sort_type" => "ASC"));
			
			return $datas;
		}
		
		public function tail($limit=5){
			formatLog("limit: $limit");
			
			$datas = $this->query(array("limit" => $limit, "sort_type" => "DESC"));
			
			// 讀取資料庫時做了倒序讀取，返回前須再倒序一次
			$datas = array_reverse($datas);
			
			return $datas;
		}
				
		// 更新 UPDATE `TRADE_RECORD` SET `SELL_PRICE` = '32.0' WHERE `TRADE_RECORD`.`NUMBER` = 1;
		public function update($data_array) {
			$where_list = array();
			$setting_list = array();
			
			foreach ($data_array as $key => $value) {
				if(in_array($key, $this->update_keys, true)){
					$where_list[] = "`$key` = '$value'";
				}else{
					$setting_list[] = "`$key` = '$value'";
				}
			}
			
			// implode = join
			$where = Database::sqlAnd($where_list);
			$setting = implode(",", $setting_list);
			$this->sql = "UPDATE $this->table SET $setting WHERE $where";
			$result = $this->db->prepare($this->sql);
			$result->execute();
		}
		
		/**
		 * 這段可以刪除資料庫中的資料
		 
		 TODO: deleteTable
		 */
		public function delete($table, $data_array) {
			$where = array();
			
			foreach ($data_array as $key => $value) {				
				$where_list[] = "$key=:$key";
			}
			
			// implode = join
			$where = implode(",", $where_list);
			// echo "<p>$where: $where</p>";
			
			$result = json_encode($where);
			// echo "<p>where: $result</p>";
			
			$this->sql = "DELETE FROM $table WHERE $where";
			// echo "<p>sql: $this->sql</p>";
			
			$result = $this->db->prepare($this->sql);
			$result->execute($data_array);
		}

		/**
		 * @return string
		 * 這段會把最後執行的語法回傳給你
		 */
		public function getLastSql() {
			return $this->sql;
		}

		/**
		 * @return int
		 * 主要功能是把新增的 ID 傳到物件外面
		 */
		public function getLastId() {
			return $this->db->lastInsertId();
		}
		
		/**
		 * @return string
		 * 取出物件內的錯誤訊息
		 */
		public function getErrorMessage()
		{
			return $this->error_message;
		}
	}
?>