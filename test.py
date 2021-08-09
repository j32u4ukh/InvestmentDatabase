import requests
from bs4 import BeautifulSoup
import urllib
import urllib.request
import urllib.error
import json
from pprint import pprint
from random import randint
import time

url = "https://webcapitalapiinvestment.000webhostapp.com/capitals/"

# GET
# params = {"mode": "all", "type": "capital"}
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
# datas = [{'NUMBER': '3',
#           'FLOW': '421595.0',
#           'STOCK': '421595.0'},
#          {'NUMBER': '4',
#           'FLOW': '50000.0',
#           'STOCK': '50000.0'}
#          ]
# data = {"rest": "update", "mode": "multi",
#         "datas": json.dumps(datas)}
# response = requests.post(url, data=data)

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
