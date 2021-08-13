import requests
from bs4 import BeautifulSoup
import urllib
import urllib.request
import urllib.error
import json
from pprint import pprint
from random import randint
import time

url = "https://webcapitalapiinvestment.000webhostapp.com/day_ohlcs/"

# GET
# params = {"mode": "all", "stock_id": "2330"}
# response = requests.get(url, params=params)
# print("response.url:", response.url)

# POST - add(mode: one)
# data = {"rest": "add", "mode": "one", "STOCK_ID": "6172", "PRICE": "42.00"}

# POST - add(mode: multi)
# datas = [{'TIME': '2021-01-01',
#           'OPEN': '10',
#           'HIGH': '20',
#           'LOW': '8',
#           'CLOSE': '15',
#           'VOL': 345},
#          {'TIME': '2021-01-02',
#           'OPEN': '15',
#           'HIGH': '21',
#           'LOW': '13',
#           'CLOSE': '18',
#           'VOL': 455}]
# data = {"rest": "add", "mode": "multi", "stock_id": "2330",
#         "datas": json.dumps(datas)}
# response = requests.post(url, data=data)

# POST - update(mode: one)
# data = {"rest": "update", "mode": "one", "GUID": "2b0232374d954a5cb1fcb735045cb20d", "PRICE": "42.00"}
# response = requests.post(url, data=data)

# POST - update(mode: multi)
datas = [{'TIME': '2021-01-01', 'OPEN': '16', 'VOL': '340'},
         {'TIME': '2021-01-02', 'OPEN': '18', 'VOL': '355'}]
data = {"rest": "update", "mode": "multi", "stock_id": "00646",
        "datas": json.dumps(datas)}
response = requests.post(url, data=data)

# POST - delete
# data = {"rest": "delete", "stock_id": "00757", "TIME": "2021-01-03"}
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
