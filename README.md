# InvestmentDatabase

將投資相關數據獨立提供與維護

## database/database.php

封裝 SQL 指令，使指令呼叫更為便捷。

```PHP
// 高階 select 傳入參數為 array，使得參數使用更為彈性
public function query($params = array()){
	$columns = defaultMapValue($params, "colums", null);
	$where = defaultMapValue($params, "where", null);
	$sort_by = defaultMapValue($params, "sort_by", $this->sort_by);
	$sort_type = defaultMapValue($params, "sort_type", "ASC");
	$limit = defaultMapValue($params, "limit", null);
	$offset = defaultMapValue($params, "offset", 0);

	$results = $this->select(null, $columns, $where, $sort_by, $sort_type, $limit, $offset);			
	$datas = array();

	foreach($results as $result){
		$data = array();

		// 將欄位名稱對應的數據加入 $data
		foreach($result as $key => $value){
			if(in_array($key, $this->sql_columns, true)){
				$data[$key] = $value;
			}
		}

		// 將每一筆 $data 加入 $datas
		$datas[] = $data;
	}

	return $datas;
}
```

query 的使用情境如下：

```PHP
// 取得最前面 $limit 筆資料(預設取得所有欄位)
$datas = $this->query(array("limit" => $limit, "sort_type" => "ASC"));

// 取得最前面 $limit 筆資料(挑選 "ID", "PRICE" 欄位)
$datas = $this->query(array("columns"=>{"ID", "PRICE"}, "limit" => $limit, "sort_type" => "ASC"));
```

## PHP 筆記

* 根據執行當下的網址路徑，影響了各腳本當中的相對路徑，因此改用 $_SERVER['DOCUMENT_ROOT'] 取得根目錄，再利用絕對路徑去指定檔案

## REST 操作

url: /api/sources/

* GET: 取得數據
* POST: 寫入數據
* PUT: 更新數據
* DELETE: 刪除數據

* API 返回的 json 數據應以 `<p></p>` 包住，以利和其他 html 標籤區分

## TABLE: STOCK_LIST

```
CREATE TABLE `database_name`.`STOCK_LIST` ( 
`STOCK_ID` VARCHAR(10) NOT NULL , 
`NAME` VARCHAR(10) NOT NULL , 
`PRICE` VARCHAR(10) NOT NULL , 
PRIMARY KEY (`STOCK_ID`)
) ENGINE = InnoDB;
```

1. STOCK_ID:
2. NAME:
3. PRICE:
	
## TABLE: "DAY_{stock_id}"

```
CREATE TABLE `database_name`.`DAY_00646` ( 
`TIME` VARCHAR(10) NOT NULL , 
`OPEN` VARCHAR(10) NOT NULL , 
`HIGH` VARCHAR(10) NOT NULL , 
`LOW` VARCHAR(10) NOT NULL , 
`CLOSE` VARCHAR(10) NOT NULL , 
`VOL` INT(10) NOT NULL , 
PRIMARY KEY (`TIME`(10))
) ENGINE = InnoDB;
```  

1. TIME:
2. OPEN:
3. high:
4. HIGH:
5. CLOSE:
6. VOL:

## TABLE: CAPITAL

```
CREATE TABLE `database_name`.`CAPITAL` ( 
`NUMBER` INT NOT NULL AUTO_INCREMENT , 
`TIME` VARCHAR(10) NOT NULL , 
`USER` VARCHAR(10) NOT NULL , 
`TYPE` VARCHAR(5) NOT NULL , 
`FLOW` VARCHAR(10) NOT NULL , 
`STOCK` VARCHAR(10) NOT NULL , 
PRIMARY KEY (`NUMBER`)
) ENGINE = InnoDB;
```

1. NUMBER:
2. TIME: 
3. USER: 
4. TYPE:
5. FLOW:
6. STOCK:

資金類型:

1. CID: 資本增減(Capital increase or decrease)，系統外部對資金做增加或減少，將用於衡量報酬率
2. PL: 損益，由交易所產生的資金變化，根據 CID 比例進行分配

備註:

* 當初創建這個表時，PRIMARY KEY 的部份給它設置了長度，導致一直無法順利建立。
* AUTO_INCREMENT 的項目應使用 INT 類型



## TABLE: INVENTORY

```
CREATE TABLE `database_name`.`INVENTORY` ( 
`GUID` VARCHAR(32) NOT NULL , 
`TIME` VARCHAR(10) NOT NULL , 
`STOCK_ID` VARCHAR(10) NOT NULL , 
`PRICE` VARCHAR(10) NOT NULL , 
PRIMARY KEY (`GUID`)
) ENGINE = InnoDB;
```

1. GUID:
2. TIME: 
3. STOCK_ID: 
4. PRICE: 

## TABLE: TRADE_RECORD

```
CREATE TABLE `id17236763_stock_data`.`TRADE_RECORD` ( 
`NUMBER` VARCHAR(10) NOT NULL , 
`STOCK_ID` VARCHAR(10) NOT NULL , 
`BUY_TIME` VARCHAR(10) NOT NULL , 
`SELL_TIME` VARCHAR(10) NOT NULL , 
`BUY_PRICE` VARCHAR(10) NOT NULL , 
`SELL_PRICE` VARCHAR(10) NOT NULL , 
`VOL` VARCHAR(10) NOT NULL , 
`BUY_COST` VARCHAR(10) NOT NULL , 
`SELL_COST` VARCHAR(10) NOT NULL , 
`REVENUE` VARCHAR(10) NOT NULL , 
PRIMARY KEY (`NUMBER`)
) ENGINE = InnoDB;
```

1. NUMBER: 
2. STOCK_ID: 
3. BUY_TIME: 
4. SELL_TIME: 
5. BUY_PRICE: 
6. SELL_PRICE: 
7. VOL: 
8. BUY_COST: 
9. SELL_COST: 
10. REVENUE:

# Other SQL

DELETE FROM `STOCK_LIST` WHERE `STOCK_LIST`.`STOCK_ID` = '8146'
