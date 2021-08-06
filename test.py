import requests
from bs4 import BeautifulSoup
import urllib
import urllib.request
import urllib.error
import json
from pprint import pprint
from random import randint
import time

url = "https://webcapitalapiinvestment.000webhostapp.com/trade_records/"

# GET
# params = {"mode": "tail", "limit": "3"}
# response = requests.get(url, params=params)
# print("response.url:", response.url)

# POST - add(mode: one)
# data = {"REST":"add", "MODE": "one", "stock_id": "5519", "buy_time": "2021-04-07", "sell_time": "2021-05-04",
#         "buy_price": "19.10", "sell_price": "22.25", "vol": "1",
#         "buy_cost": "19120.0", "sell_cost": "86", "revenue": "3044.00"}

# POST - add(mode: multi)
# datas = [{"stock_id": "5519", "buy_time": "2021-04-07", "sell_time": "2021-05-04",
#           "buy_price": "19.10", "sell_price": "22.25", "vol": "1",
#           "buy_cost": "19120.0", "sell_cost": "86", "revenue": "3044.00"},
#          {"stock_id": "1417", "buy_time": "2021-04-12", "sell_time": "2021-05-04",
#           "buy_price": "12.55", "sell_price": "13.90", "vol": "1",
#           "buy_cost": "12570.00", "sell_cost": "61", "revenue": "1269.00"}
#          ]
# data = {"REST": "add", "MODE": "multi",
#         "datas": json.dumps(datas)}
# response = requests.post(url, data=data)

# POST - update(mode: one)
# data = {"REST": "update", "MODE": "one", "STOCK_ID": "5519", "BUY_TIME": "2021-04-07", "SELL_TIME": "2021-05-04",
#         "BUY_PRICE": "19.10", "SELL_PRICE": "22.25", "VOL": "1",
#         "BUY_COST": "19120.0", "SELL_COST": "86", "REVENU": "3044.00"}
# response = requests.post(url, data=data)

# POST - update(mode: multi)
# datas = [{"STOCK_ID": "5519", "BUY_TIME": "2021-04-07", "SELL_TIME": "2021-05-04",
#           "BUY_PRICE": "19.10", "SELL_PRICE": "22.25", "VOL": "1",
#           "BUY_COST": "19120.0", "SELL_COST": "86.0", "REVENU": "3044.00"},
#          {"STOCK_ID": "1417", "BUY_TIME": "2021-04-12", "SELL_TIME": "2021-05-04",
#           "BUY_PRICE": "12.55", "SELL_PRICE": "13.90", "VOL": "1",
#           "BUY_COST": "12570.00", "SELL_COST": "61.0", "REVENU": "1269.00"}
#          ]
datas = [{"STOCK_ID": "5519", "BUY_TIME": "2021-04-07", "SELL_TIME": "2021-05-04", "SELL_COST": "86.0"},
         {"STOCK_ID": "1417", "BUY_TIME": "2021-04-12", "SELL_TIME": "2021-05-04", "SELL_COST": "61.0"}]
data = {"REST": "update", "MODE": "multi",
        "datas": json.dumps(datas)}
response = requests.post(url, data=data)

# POST - delete
# data = {"REST": "delete", "STOCK_ID": "5519", "BUY_TIME": "2021-04-07", "SELL_TIME": "2021-05-04"}
# response = requests.post(url, data=data)

if response.status_code == requests.codes.ok:
    print(f"[{response.status_code}] OK")
    html = BeautifulSoup(response.text, 'html.parser')

    try:
        results = html.find_all("p")

        for result in results:
            if 'api' in result["class"]:
                api_result = json.loads(result.text)
                pprint(api_result)

    except json.decoder.JSONDecodeError:
        pprint(html.text)
