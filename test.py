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

# POST - add
# data = {"rest":"add", "mode": "one", "stock_id": "5519", "buy_time": "2021-04-07", "sell_time": "2021-05-04",
#         "buy_price": "19.10", "sell_price": "22.25", "vol": "1",
#         "buy_cost": "19120.0", "sell_cost": "86", "revenue": "3044.00"}
# response = requests.post(url, data=data)

# POST - update
data = {"REST": "update", "STOCK_ID": "5519", "BUY_TIME": "2021-04-07", "SELL_TIME": "2021-05-04",
        "BUY_PRICE": "19.10", "SELL_PRICE": "22.25", "VOL": "1",
        "BUY_COST": "19120.0", "SELL_COST": "86", "REVENU": "3044.00"}
response = requests.post(url, data=data)

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
