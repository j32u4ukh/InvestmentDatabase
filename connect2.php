<?php
    // 方法2 - 使用mysqli（面向對象）連接數據庫
    $servername = "localhost";
    $username = "id17236763_j32u4ukh";
    $password = ">_AJeTJOD#bH_l2(";
    $database = "id17236763_stock_data";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";
?>

