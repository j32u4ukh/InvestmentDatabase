<html>
    <head>
        <title>交易歷史紀錄</title>
    </head>
    <body>
		<?php
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/trade_records/read.php");
		?>
<!-- 		<form method="POST" action="trade_record.php">
        <input type="text" name="post_msg" placeholder="請輸入傳送的文字">
        <input type="submit" value="送出">
        <input type="reset" value="重填">
		</form> -->
		
		<?php
			// GET
            if(isset($_GET["read"])){
				$read = $_GET["read"];
                formatLog("read:" . $read, false);
				
				$limit = defaultMapValue($_GET, "limit", 5);
				
				$start_buy = defaultMapValue($_GET, "start_buy", null);
				$end_buy = defaultMapValue($_GET, "end_buy", null);
				
				$start_sell = defaultMapValue($_GET, "start_sell", null);
				$end_sell = defaultMapValue($_GET, "limit", null);
				
				read($read, $limit, $start_buy, $end_buy, $start_sell, $end_sell);
            }
        ?>
		
		<?php            
            if(isset($_POST["post_msg"])){
                $post_msg = $_POST["post_msg"];
                formatLog("post_msg:" . $post_msg, false);
            }
        ?>
    </body>
</html>