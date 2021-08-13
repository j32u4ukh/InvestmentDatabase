<html>
    <head>
        <title>交易歷史紀錄 API</title>
    </head>
    <body>	
<!--         <form action='./index.php' method='post'>
			rest:<input type='text' id='rest' name='rest'><br>
		    mode:<input type='text' id='mode' name='mode'><br>
		    guid:<input type='text' id='GUID' name='GUID'><br>
			price:<input type='text' id='PRICE' name='PRICE'><br>
			<input type='submit' value='Submit'>
		</form> -->
    		
        <?php
            include_once ($_SERVER['DOCUMENT_ROOT'] . "/utils.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/day_ohlcs/read.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/day_ohlcs/add.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/day_ohlcs/update.php");
			include_once ($_SERVER['DOCUMENT_ROOT'] . "/day_ohlcs/delete.php");
			
			// GET
            if(isset($_GET["mode"])){				
				read($_GET);
            }
			
			if(isset($_POST["rest"])){
			    echo "POST: " . json_encode($_POST) . "<br>";
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