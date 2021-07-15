<?php
	class Connect{
		private const server = "localhost";
		private const user = "id17236763_j32u4ukh";
		private const password = ">_AJeTJOD#bH_l2(";
		private const database = "id17236763_stock_data";
		public $conn = NULL;
		
		public function __construct($type = "pdo"){
			switch($type){
				case "pdo":
					$conn = $this->connectPDO();
					break;
				case "sqli":
					$conn = $this->connectMysqli();
					break;
				case "sqli_connect":
					$conn = $this->connectMysqliConnect();
					break;
			}
		}
		
		public function __destruct() {
			print "Destroying\n";
		}
		
		public static function connectPDO(){		
			try {
				$conn = new PDO("mysql:host=" . self::server . "; dbname=" . self::database, self::user, self::password);

				// set the PDO error mode to exception
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				echo "<p>Connected successfully</p>";
				
				return $conn;
			}
			catch(PDOException $e) {
				echo "<p>Connection failed: " . $e->getMessage() . "</p>";
				
				return null;
			}
		}
		
		public static function connectMysqli(){
			// Create connection
			$conn = new mysqli(self::server, self::user, self::password, self::database);

			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
				
				return null;
			}else{
				echo "Connected successfully";		
				
				return $conn;
			}
		}
		
		public static function connectMysqliConnect(){
			// Create connection
			$conn = mysqli_connect(self::server, self::user, self::password, self::database);

			// Check connection
			if (!$conn) {
				die("Connection failed: " . mysqli_connect_error());
				
				return null;
			}else{
				echo "Connected successfully";		
				
				return $conn;
			}
		}
	}
?>