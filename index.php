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
			$result = $db->head(5);
			echo json_encode($result);
		?>
    </body>
</html>