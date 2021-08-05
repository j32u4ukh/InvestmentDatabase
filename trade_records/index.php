<html>
    <head>
        <title>交易歷史紀錄 API</title>
    </head>
    <body>
		<!--<form action="./index.php" method="post">-->
		<!--	<input type="text" id="mode" name="mode">mode:<br><br>-->
		<!--	<input type="text" id="stock_id" name="stock_id">stock_id:<br><br>			  -->
		<!--	<input type="text" id="buy_time" name="buy_time">buy_time:<br><br>			  -->
		<!--	<input type="text" id="sell_time" name="sell_time">sell_time:<br><br>-->

		<!--	<input type="text" id="buy_price" name="buy_price">buy_price:<br><br>-->
		<!--	<input type="text" id="sell_price" name="sell_price">sell_price:<br><br>-->
		<!--	<input type="text" id="vol" name="vol">vol:<br><br>-->
		<!--	<input type="text" id="buy_cost" name="buy_cost">buy_cost:<br><br>-->
		<!--	<input type="text" id="sell_cost" name="sell_cost">sell_cost:<br><br>-->
		<!--	<input type="text" id="revenue" name="revenue">revenue:<br><br>-->
		<!--	<input type="submit" value="Submit">-->
		<!--</form>-->
			
        <?php
            include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/trade_records/read.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/trade_records/add.php");
			
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
                   
            if(isset($_POST["mode"])){
				$mode = $_POST["mode"];
				
				if($mode == "one"){
					$stock_id = $_POST["stock_id"];
					formatLog("stock_id:" . $stock_id, false);
					
					/*array(	"stock_id"=>"2329", "buy_time"=>"2021-04-08", "sell_time"=>"2021-05-04", 
							"buy_price"=>"17.40", "sell_price"=>"17.45", "vol"=>"1", 
							"buy_cost"=>"17420.0", "sell_cost"=>"72", "revenue"=>"-42.00")*/
							
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
            }
			
			// TODO: Update / Delete
			// https://www.w3jar.com/crud-rest-api-in-php-pdo/
			// https://notfalse.net/45/http-head-put-delete#PUT
			// https://www.php.net/manual/zh/features.file-upload.put-method.php
			// https://thisinterestsme.com/send-put-request-php/
        ?>
    </body>
</html>