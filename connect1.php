<?php
    // 方法 1 - 使用 PDO 連接到數據庫（推薦）
    $servername = "localhost";
    $username = "id17236763_j32u4ukh";
    $password = ">_AJeTJOD#bH_l2(";
    $database = "id17236763_stock_data";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

        // set the PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected successfully";
    }
    catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
?>