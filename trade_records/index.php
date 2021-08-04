<html>
    <head>
        <title>交易歷史紀錄 API</title>
    </head>
    <body>
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
					
					add($stock_id, $buy_time, $sell_time, $buy_price, $sell_price, $vol, $buy_cost, $sell_cost, $revenue);
					
				}else{
					formatLog("一次添加多筆模式，待實作", false);
				}
            }
        ?>
    </body>
</html>