<?php
    // 方法 3 — 使用 mysqli_connect 連接到數據庫（程序）
    $servername = "localhost";
    $username = "id17236763_j32u4ukh";
    $password = ">_AJeTJOD#bH_l2(";
    $database = "id17236763_stock_data";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    echo "Connected successfully";
?>