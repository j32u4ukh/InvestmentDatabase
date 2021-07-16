<html>
    <head>
        <title>Web Capital API Investment</title>
    </head>
    <body>
        <p>Hello World!</p>
        <p>// TODO: 首頁呈現現有庫存 & 最近幾筆交易紀錄以及整體策略表現</p>
        <p>// TODO: 各個投資者利用帳密可登入個別頁面，看到自己的資金變化</p>
		<?php
			include "database.php";
			$db = connectToStockData();
			// $db->insert("STOCK_LIST", array("STOCK_ID"=> "2330", "NAME"=> "", "PRICE"=> "614.00"));
			$db->update("STOCK_LIST", array("STOCK_ID"=> "2330", "NAME"=> "台積電", "PRICE"=> "615.00"), "STOCK_ID", "2330");
			
			$result = $db->select("STOCK_LIST");
			$json_result = json_encode($result);
			echo "<p>$json_result</p>";
		?>
    </body>
</html>