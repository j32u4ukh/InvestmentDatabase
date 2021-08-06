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
						$mode = $_POST["mode"];
						
						if($mode == "one"){
							$stock_id = $_POST["stock_id"];
							formatLog("stock_id:" . $stock_id, false);
							
							$buy_time = $_POST["buy_time"];
							$sell_time = $_POST["sell_time"];
							
							$buy_price = $_POST["buy_price"];
							$sell_price = $_POST["sell_price"];
							
							$vol = $_POST["vol"];
							
							$buy_cost = $_POST["buy_cost"];
							$sell_cost = $_POST["sell_cost"];
							$revenue = $_POST["revenue"];
							
							add($stock_id, $buy_time, $sell_time, $buy_price, $sell_price, $vol, 
								$buy_cost, $sell_cost, $revenue);
							
						}else{
							formatLog("一次添加多筆模式，待實作", false);
							$stock_id = $_POST["stock_id"];
							$buy_time = $_POST["buy_time"];
							$sell_time = $_POST["sell_time"];
							$buy_price = $_POST["buy_price"];
							$sell_price = $_POST["sell_price"];
							$vol = $_POST["vol"];
							$buy_cost = $_POST["buy_cost"];
							$sell_cost = $_POST["sell_cost"];
							$revenue = $_POST["revenue"];
							
							$datas = array(
								array("stock_id"=>"$stock_id", "buy_time"=>"$buy_time", "sell_time"=>"$sell_time",
									  "buy_price"=>"$buy_price", "sell_price"=>"$sell_price", "vol"=>"$vol",
									  "buy_cost"=>"$buy_cost", "sell_cost"=>"$sell_cost", "revenue"=>"$revenue")
							);
										
							echo "<p>" . json_encode($datas) . "</p>";
						}

						break;
					case "update":
						update($_POST);
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