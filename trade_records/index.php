<html>
    <head>
        <title>交易歷史紀錄</title>
    </head>
    <body>        
		<form method="POST" action="trade_record.php">
        <input type="text" name="post_msg" placeholder="請輸入傳送的文字">
        <input type="submit" value="送出">
        <input type="reset" value="重填">
		</form>
		
		<?php
            include_once "utils.php";
            
            if(isset($_POST["post_msg"])){
                $post_msg = $_POST["post_msg"];
                formatLog("post_msg:" . $post_msg, false);
            }
        ?>
    </body>
</html>