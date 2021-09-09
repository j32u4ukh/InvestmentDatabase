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
				read($_GET);
            }
			
			if(isset($_POST["rest"])){
				$rest = $_POST["rest"];
				
				switch($rest){
					case "add":
						$mode = $_POST["mode"];
						
						if($mode == "one"){
							add($_POST);
							
						}else{
							addMultiDatas($_POST);
						}

						break;
					case "update":
						$mode = $_POST["mode"];
						
						switch($mode){
						    case "one":
						       update($_POST);
						       break;
						    case "renumber":
						        renumber();
						    default:
						        updateMultiDatas($_POST);
						        break;
						}
						
						break;
					case "delete":
						delete($_POST);
						break;
				}
			}
        ?>
    </body>
</html>