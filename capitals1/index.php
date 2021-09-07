<html>
    <head>
        <title>資金損益紀錄 API</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>	
        <?php
            include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/database/capital.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/capitals1/read.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/capitals1/add.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/capitals1/update.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/capitals1/delete.php");
			
			$db = new Capital();
			
			// 日期	柏緯資金	媽媽資金	總資金	柏緯總投資	柏緯報酬率	媽媽總投資	媽媽報酬率	總投資																		
			// 2021/06/28	421595	50000	471595	421595	1	50000	1	471595																		
			// $datas = array(
				// array("TIME"=>"2021-06-28", "USER"=>"j32u4ukh", "TYPE"=>"capital", 
					  // "FLOW"=>"421595", "STOCK"=>"421595", "REMARK"=>"原始資金"),
				// array("TIME"=>"2021-06-28", "USER"=>"ahuayeh", "TYPE"=>"capital", 
					  // "FLOW"=>"50000", "STOCK"=>"50000", "REMARK"=>"原始資金")
			// );
			
			// $db->insert($datas);
			// $data = $db->tail(5);
			// $result = array("status"=>"success", 
							// "data"=>$data);
							
			// echo "<p class='api'>" . json_encode($result) . "</p>";
			
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
        ?>
    </body>
</html>