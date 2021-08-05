<html>
    <head>
        <title>Web Capital API Investment</title>
    </head>
    <body>
        <p>Hello World!</p>
        <p>// TODO: 首頁呈現現有庫存 & 最近幾筆交易紀錄以及整體策略表現</p>
        <p>// TODO: 各個投資者利用帳密可登入個別頁面，看到自己的資金變化</p>
		<?php
			include_once "database/trade_record.php";
			include_once "utils.php";

			$db = new TradeRecord();
			$db->update(array("STOCK_ID"=>"2329", "BUY_TIME"=>"2021-04-08", "SELL_TIME"=>"2021-05-04", 
							"BUY_PRICE"=>"17.40", "SELL_PRICE"=>"17.45", "VOL"=>"1", 
							"BUY_COST"=>"17420.0", "SELL_COST"=>"72.0", "REVENUE"=>"-42.00"));
			echo "<br><br><br><br>";
			
			$result = $db->tail(5);			
			echo json_encode($result);
		?>
    </body>
</html>