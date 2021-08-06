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
# data = {"rest":"add", "mode": "one", "stock_id": "5519", "buy_time": "2021-04-07", "sell_time": "2021-05-04",
#         "buy_price": "19.10", "sell_price": "22.25", "vol": "1",
#         "buy_cost": "19120.0", "sell_cost": "86", "revenue": "3044.00"}

# POST - add(mode: multi)
# datas = [{"NUMBER": "1", "STOCK_ID": "5519", "BUY_TIME": "2021-04-07", "SELL_TIME": "2021-05-04",
#           "BUY_PRICE": "19.10", "SELL_PRICE": "22.25", "VOL": "1",
#           "BUY_COST": "19120.0", "SELL_COST": "86.0", "REVENUE": "3044.00"},
#          {"NUMBER": "2", "STOCK_ID": "1417", "BUY_TIME": "2021-04-12", "SELL_TIME": "2021-05-04",
#           "BUY_PRICE": "12.55", "SELL_PRICE": "13.90", "VOL": "1",
#           "BUY_COST": "12570.00", "SELL_COST": "61.0", "REVENUE": "1269.00"}
#          ]
# data = {"rest": "add", "mode": "multi",
#         "datas": json.dumps(datas)}
# response = requests.post(url, data=data)

# POST - update(mode: one)
# data = {"rest": "update", "mode": "one", "STOCK_ID": "5519", "BUY_TIME": "2021-04-07", "SELL_TIME": "2021-05-04",
#         "BUY_PRICE": "19.10", "SELL_PRICE": "22.25", "VOL": "1",
#         "BUY_COST": "19120.0", "SELL_COST": "86", "REVENU": "3044.00"}
# response = requests.post(url, data=data)

# POST - update(mode: multi)
datas = [{'NUMBER': '5',
          'STOCK_ID': '2012',
          'BUY_TIME': '2021-03-23',
          'SELL_TIME': '2021-04-23',
          'BUY_PRICE': '0',
          'SELL_PRICE': '0',
          'VOL': '0',
          'BUY_COST': '0',
          'SELL_COST': '0',
          'REVENUE': '590.0'},
         {'NUMBER': '6',
          'STOCK_ID': '2419',
          'BUY_TIME': '2021-04-28',
          'SELL_TIME': '2021-05-03',
          'BUY_PRICE': '25.55',
          'SELL_PRICE': '23.55',
          'VOL': '1',
          'BUY_COST': '25570.00',
          'SELL_COST': '90',
          'REVENUE': '-2110.0'}
         ]
data = {"rest": "update", "mode": "multi",
        "datas": json.dumps(datas)}
response = requests.post(url, data=data)

# POST - delete
# data = {"rest": "delete", "NUMBER": "2"}
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
