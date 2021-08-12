import requests
from bs4 import BeautifulSoup
import urllib
import urllib.request
import urllib.error
import json
from pprint import pprint
from random import randint
import time

url = "https://webcapitalapiinvestment.000webhostapp.com/inventories/"

# GET
# params = {"mode": "all", "stock_id": "2812"}
# response = requests.get(url, params=params)
# print("response.url:", response.url)

# POST - add(mode: one)
# data = {"rest":"add", "mode": "one", "stock_id": "5519", "buy_time": "2021-04-07", "sell_time": "2021-05-04",
#         "buy_price": "19.10", "sell_price": "22.25", "vol": "1",
#         "buy_cost": "19120.0", "sell_cost": "86", "revenue": "3044.00"}

# POST - add(mode: multi)
# datas = [{"GUID": "2b0232374d954a5cb1fcb735045cb20d", "TIME": "2021-07-22", "STOCK_ID": "6172", "PRICE": "42.00"}]
# data = {"rest": "add", "mode": "multi",
#         "datas": json.dumps(datas)}
# response = requests.post(url, data=data)

# POST - update(mode: one)
data = {"rest": "update", "mode": "one", "GUID": "2b0232374d954a5cb1fcb735045cb20d", "PRICE": "42.00"}
response = requests.post(url, data=data)

# POST - update(mode: multi)
# datas = [{'GUID': '2b0232374d954a5cb1fcb735045cb20d',
#           'PRICE': '42.0'},
#          {'GUID': 'c0a5e6f5c3544da4b7857beaceee55c1',
#           'PRICE': '21.20'}
#          ]
# data = {"rest": "update", "mode": "multi",
#         "datas": json.dumps(datas)}
# response = requests.post(url, data=data)

# POST - delete
# data = {"rest": "delete", "GUID": "2b0232374d954a5cb1fcb735045cb20d"}
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
