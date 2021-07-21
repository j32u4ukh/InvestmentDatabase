<?php
	function connectAccess(){
		$keys = array("server", "user", "password", "database");
		$file = fopen("database/access.txt", "r");	
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
	class Database{
		protected $database = null;
		protected $db = null;
		protected $table = null;
		
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
		
		// 建立跟資料庫的連接，並設定語系是萬國語言以支援中文
		protected function connectPDO($server, $user, $password, $database){		
			try {
				$this->db = new PDO("mysql:host=$server; charset=utf8mb4; dbname=$database", $user, $password);

				// set the PDO error mode to exception
				$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				echo "<p>Connected successfully</p>";
			}
			catch(PDOException $e) {
				echo "<p>Connection failed: " . $e->getMessage() . "</p>";
			}
		}
		
		public function getTable($table, $table_definition){
			/* 若該表格不存在，則建立表格
			
			:param table_name: 表格名稱
			:param table_definition: 表格定義
			:return: 
			*/
			$this->sql = "CREATE TABLE IF NOT EXISTS `$this->database`.`$table` ({$table_definition}) ENGINE = InnoDB;";
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
				$this->error_message = "<p class='bg-danger'>$e->getMessage()</p>";
				echo $this->error_message;
			}
		}
		
		// TODO: 檢查資料表是否存在 https://stackoverflow.com/questions/1717495/check-if-a-database-table-exists-using-php-pdo
		
		// 自己實作的讀取資料庫
		public function select($table=null, $columns=array(), $n_limit=5){
			if($table === null){
				$table = $this->table;
			}
			
			if(count($columns) == 0){
				$format_columns = "*";
			}else{
				// implode = join
				$format_columns = implode(",", $columns);
			}
			
			$this->sql = "SELECT $format_columns FROM $table LIMIT $n_limit";
			
			try {
				$result = $this->db->prepare($this->sql);
				$result->execute();
				
				return $result->fetchAll();
				
			} catch(PDOException $e) {
				$this->error_message = "<p class='bg-danger'> $e->getMessage() </p>";
			}
		}
		
		/**
		 * query 提供較大的彈性來讀取資料庫，但我目前還沒使用到，因此還沒維護
		 */
		public function query($table, $condition=array(), $order_by="DESC", $fields="*", $n_limit=5){
			if(empty($table)){
				return false;
			}
			
			// if(!isset($data_array) OR count($data_array) == 0){
				// return false;
			// }
			
			if(is_numeric($n_limit)){
				if($n_limit > 0){
					$limit = "LIMIT $n_limit";
				}else{
					$limit = "";
				}
			}else{
				$limit = "";
			}
			
			if(empty($condition)){
				$condition = 1;
			}
			
			// if(empty($order_by)){
				// $order_by = 1;
			// }
			
			// if(empty($fields)){
				// $fields = "*";
			// }
			
			$this->sql = "SELECT $fields FROM $table WHERE $condition ORDER BY $order_by $limit";
			
			try {
				$result = $this->db->prepare($this->sql);
				// $result->execute($data_array);
				$result->execute();
				
				return $result->fetchAll();
				
			} catch(PDOException $e) {
				$this->error_message = "<p class='bg-danger'> $e->getMessage() </p>";
			}
		}
		
		// 為 INSERT 準備 sql 指令與欄位
		public function insertPrepareColumns($table, $data){
			$temp = array();
		
			foreach($data as $key => $value){
				$temp[] = $key;
			}
			
			// implode = join
			$columns = implode(",", $temp);
			$sql_prepare = "INSERT INTO $table ( $columns ) VALUES ";
			
			return $sql_prepare;
		}
		
		// 為 INSERT 準備 Value 要插入的空格
		public function insertPrepareValues($data, $index){
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
		public function insertPrepareDatas($data_array, $data, $index){
			$temp = array();
		
			foreach($data as $key => $value){
				// $dataAdd[':house_id_'.$n] = $_d['house_id'];
				$data_array[":$key" . "_" . $index] = $value;
			}
			
			return $data_array;
		}
		
		// 呼叫一次，可插入一到多筆數據
		public function insert($table, $datas) {
			$sql_prepare = $this->insertPrepareColumns($table, $datas[0]);
			$n_data = count($datas);
			$values_prepare_list = array();
			$data_array = array();
			
			for ($i = 0; $i < $n_data; $i++) {
			  $data = $datas[$i];
			  $values_prepare_list[] = $this->insertPrepareValues($data, $i);
			  $data_array = $this->insertPrepareDatas($data_array, $data, $i);
			}
			
			// INSERT INTO table_name (KEY1, KEY2) VALUES (:KEY1, :KEY2), (:KEY1, :KEY2), (:KEY1, :KEY2);
			$this->sql = $sql_prepare . implode(",", $values_prepare_list);
			echo "<p>sql: $this->sql</p>";
			print json_encode($data_array);
			$result = $this->db->prepare($this->sql);
			$result->execute($data_array);
			
			$this->last_id = $this->db->lastInsertId();
		}

		/**
		 * 這段可以更新資料庫中的資料
		 */
		public function update($table, $data_array, $key_column, $value) {		
			if(count($data_array) == 0){
				return false;
			}
			
			$setting_list = array();
			
			foreach ($data_array as $key => $value) {
				if($key == $key_column){
					continue;
				}
				
				$setting_list[] = "$key=:$key";
			}
			
			// implode = join
			$setting = implode(",", $setting_list);
			echo "<p>setting: $setting</p>";

			$this->sql = "UPDATE $table SET $setting WHERE $key_column = :$key_column";
			echo "<p>sql: $this->sql</p>";
			
			$result = $this->db->prepare($this->sql);                       
			$result->execute($data_array);
		}
		
		/**
		 * 這段可以刪除資料庫中的資料
		 */
		public function delete($table, $data_array) {
			$where = array();
			
			foreach ($data_array as $key => $value) {				
				$where_list[] = "$key=:$key";
			}
			
			// implode = join
			$where = implode(",", $where_list);
			echo "<p>$where: $where</p>";
			
			$result = json_encode($where);
			echo "<p>where: $result</p>";
			
			$this->sql = "DELETE FROM $table WHERE $where";
			echo "<p>sql: $this->sql</p>";
			
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
			return $this->last_id;
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