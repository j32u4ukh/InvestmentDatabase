<html>
    <head>
        <title>交易歷史紀錄 API</title>
    </head>
    <body>	
        <?php
            include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/trade_records/read.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/trade_records/add.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/trade_records/update.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/trade_records/delete.php");
			
			// GET
            if(isset($_GET["mode"])){
				$mode = $_GET["mode"];
                formatLog("mode:" . $mode, false);
				
				$limit = defaultMapValue($_GET, "limit", 5);
				
				$start_buy = defaultMapValue($_GET, "start_buy", null);
				$end_buy = defaultMapValue($_GET, "end_buy", null);
				
				$start_sell = defaultMapValue($_GET, "start_sell", null);
				$end_sell = defaultMapValue($_GET, "limit", null);
				
				read($mode, $limit, $start_buy, $end_buy, $start_sell, $end_sell);
            }
			
			if(isset($_POST["REST"])){
				$rest = $_POST["REST"];
				
				switch($rest){
					case "add":
						$mode = $_POST["MODE"];
						
						if($mode == "one"){
							add($_POST);
							
						}else{
							addMultiDatas($_POST);
						}

						break;
					case "update":
						$mode = $_POST["MODE"];
						
						if($mode == "one"){
							update($_POST);
							
						}else{
							updateMultiDatas($_POST);
						}
						break;
					case "delete":
						delete($_POST);
						break;
				}
			}
			
			// TODO: Update / Delete
			// https://www.w3jar.com/crud-rest-api-in-php-pdo/
			// https://notfalse.net/45/http-head-put-delete#PUT
			// https://www.php.net/manual/zh/features.file-upload.put-method.php
			// https://thisinterestsme.com/send-put-request-php/
        ?>
    </body>
</html>