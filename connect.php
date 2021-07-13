<?php
	$server = "localhost";
    $user = "id17236763_j32u4ukh";
    $password = ">_AJeTJOD#bH_l2(";
    $database = "id17236763_stock_data";
	
	function connectPDO(){
		try {
			$conn = new PDO("mysql:host=$server; dbname=$database", $username, $password);

			// set the PDO error mode to exception
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			echo "Connected successfully";
			
			return $conn;
		}
		catch(PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
			
			return null;
		}
	}
	
	function connectMysqli(){
		// Create connection
		$conn = new mysqli($server, $username, $password, $database);

		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
			
			return null;
		}else{
			echo "Connected successfully";		
			
			return $conn;
		}
	}
	
	function connectMysqliConnect(){
		// Create connection
		$conn = mysqli_connect($server, $username, $password, $database);

		// Check connection
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
			
			return null;
		}else{
			echo "Connected successfully";		
			
			return $conn;
		}
	}
?>