<html>
    <head>
        <title>Web Capital API Investment</title>
    </head>
    <body>
        <p>Hello World!</p>
        <p>// TODO: 首頁呈現現有庫存 & 最近幾筆交易紀錄以及整體策略表現</p>
        <p>// TODO: 各個投資者利用帳密可登入個別頁面，看到自己的資金變化</p>
		<?php
			include_once "database/stock_list.php";
			include_once "utils.php";

			$stock_list = new StockList();	
			$datas = array(
				array("STOCK_ID" => "2330", "NAME" => "台GG", "PRICE" => "312"),
				array("STOCK_ID" => "8146", "NAME" => "東哥", "PRICE" => "83")
			);
			
			$stock_list->insert($datas);
			
			$result = $stock_list->select();
			debugLog(json_encode($result));
		?>
    </body>
</html>