<html>
    <head>
        <title>交易歷史紀錄</title>
    </head>
    <body>
		<?php
			// include
            include_once "utils.php";
        ?>
<!-- 		<form method="POST" action="trade_record.php">
        <input type="text" name="post_msg" placeholder="請輸入傳送的文字">
        <input type="submit" value="送出">
        <input type="reset" value="重填">
		</form> -->
		
		<?php
			// GET
            if(isset($_GET["read"])){
				$read = $_GET["read"]
                formatLog("read:" . $read, false);
				
				$limit = defaultValue($_GET["limit"], -1);
				
				$start_buy = defaultValue($_GET["start_buy"], -1);
				$end_buy = defaultValue($_GET["end_buy"], -1);
				
				$start_sell = defaultValue($_GET["start_sell"], -1);
				$end_sell = defaultValue($_GET["limit"], -1);
				
				switch($read){
					case "head":
						break;
					case "tail":
						break;
					case "all":
						break;
				}
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