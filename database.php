<?php
	function connectToStockData(){
		$server = "localhost";
		$user = "id17236763_j32u4ukh";
		$password = ">_AJeTJOD#bH_l2(";
		$database = "id17236763_stock_data";		
		$database = new Database("localhost", "id17236763_j32u4ukh", ">_AJeTJOD#bH_l2(", "id17236763_stock_data");
		
		return $database;
	}
	
	// 參考：https://ithelp.ithome.com.tw/articles/10195426
	class Database{
		private $db = NULL;
		
		private $sql = "";
		private $last_id = 0;
		private $last_num_rows = 0;
		private $error_message = "";
		
		// 『建構式』會在物件被 new 時自動執行
		public function __construct($server, $user, $password, $database){
			$this->connectPDO($server, $user, $password, $database);
		}
		
		public function __destruct() {
			print "Destroying\n";
			$this->db = NULL;
		}
		
		// 建立跟資料庫的連接，並設定語系是萬國語言以支援中文
		private function connectPDO($server, $user, $password, $database){		
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
		
		public function execute($sql, $data_array) {
			try {
				$result = $this->db->prepare($sql);
				$result->execute($data_array);
				
				return $result->fetchAll(); 
				
			} catch(PDOException $e) {
				$this->error_message = "<p class='bg-danger'>$e->getMessage()</p>";
				echo $this->error_message;
			}
		}
		
		// 自己實作的讀取資料庫
		public function select($table, $columns=array(), $n_limit=5){
			if(count($columns) == 0){
				$format_columns = "*";
			}else{
				$format_columns = join(",", $columns);
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
		 * 這段用來讀取資料庫中的資料，回傳的是陣列資料
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

		/**
		 * 這段可以新增資料庫中的資料，並把最後一筆的 ID 存到變數中，可以用 getLastId() 取出
		 目前查到的資訊是 PDO 無法同時多筆寫入，待確認
		 */
		public function insert($table = null, $data_array = array()) {
			if($table === null){
				return false;
			}
			
			if(count($data_array) == 0){
				return false;
			}

			$tmp_col = array();
			$tmp_dat = array();

			foreach ($data_array as $key => $value) {
				$tmp_col[] = $key;
				$tmp_dat[] = ":$key";
				$prepare_array[":$key"] = $value;
			}
			
			$columns = join(",", $tmp_col);
			$data = join(",", $tmp_dat);
			echo "<p>columns: $columns</p>";
			echo "<p>data: $data</p>";
			echo "<p>prepare_array: $prepare_array</p>";
			
			// INSERT INTO table_name (KEY1, KEY2) VALUES (:KEY1, :KEY2);
			$this->sql = "INSERT INTO $table ( $columns ) VALUES ( $data )";
			echo "<p>sql: $this->sql</p>";
			$result = $this->db->prepare($this->sql);
			
			// $prepare_array = {":KEY1": [value1, value2, value3], ":KEY2": [value1, value2, value3]}
			$result->execute($prepare_array);
			
			$this->last_id = $this->db->lastInsertId();
		}

		/**
		 * 這段可以更新資料庫中的資料
		 */
		public function update($table, $data_array, $key_column, $value) {		
			if(count($data_array) == 0){
				return false;
			}
			
			// if($id == null){
				// return false;
			// }
						
			$setting_list = array();
			
			foreach ($data_array as $key => $value) {
				if($key == $key_column){
					continue;
				}
				
				$setting_list[] = "$key=:$key";
			}
			
			$setting = join(",", $setting_list);
			echo "<p>setting: $setting</p>";
			
			// for ($xx = 0; $xx < count($data_array); $xx++) {
				// list($key, $value) = each($data_array);
				// $setting_list .= $key . "=" . ':'.$key;
				// $setting_list .= "$key=:$key";
				
				// if ($xx != count($data_array) - 1){
					// $setting_list .= ",";
				// }
			// }
			
			// $data_array[$key_column] = $value;
			$this->sql = "UPDATE $table SET $setting WHERE $key_column = :$key_column";
			echo "<p>sql: $this->sql</p>";
			
			$result = $this->db->prepare($this->sql);                       
			$result->execute($data_array);
		}
		
		/**
		 * 這段可以刪除資料庫中的資料
		 */
		public function delete($table = null, $key_column = null, $id = null) {
			if ($table === null){
				return false;
			}
			
			if($id === null){
				return false;
			}
			
			if($key_column === null){
				return false;
			}

			$this->sql = "DELETE FROM $table WHERE $key_column = :$key_column";
			$result = $this->db->prepare($this->sql);
			$result->execute(array( ":$key_column" => $id));
		}

		/**
		 * @return string
		 * 這段會把最後執行的語法回傳給你
		 */
		public function getLastSql() {
			return $this->sql;
		}

		/**
		 * @param string $sql
		 * 這段是把執行的語法存到變數裡，設定成 private 只有內部可以使用，外部無法呼叫
		 */
		private function setLastSql($sql) {
			$this->sql = $sql;
		}

		/**
		 * @return int
		 * 主要功能是把新增的 ID 傳到物件外面
		 */
		public function getLastId() {
			return $this->last_id;
		}

		/**
		 * @param int $last_id
		 * 把這個 $last_id 存到物件內的變數
		 */
		private function setLastId($last_id) {
			$this->last_id = $last_id;
		}

		/**
		 * @return int
		 */
		public function getLastNumRows() {
			return $this->last_num_rows;
		}

		/**
		 * @param int $last_num_rows
		 */
		private function setLastNumRows($last_num_rows) {
			$this->last_num_rows = $last_num_rows;
		}

		/**
		 * @return string
		 * 取出物件內的錯誤訊息
		 */
		public function getErrorMessage()
		{
			return $this->error_message;
		}

		/**
		 * @param string $error_message
		 * 記下錯誤訊息到物件變數內
		 */
		private function setErrorMessage($error_message){
			$this->error_message = $error_message;
		}
	}
?>