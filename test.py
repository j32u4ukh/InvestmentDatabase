import requests
from bs4 import BeautifulSoup
import urllib
import urllib.request
import urllib.error
import json
from pprint import pprint
from random import randint
import time

url = "https://webcapitalapiinvestment.000webhostapp.com/stock_lists/"

# GET
# params = {"mode": "all", "min": "60.0", "max": "200.0", "sort": "0"}
# response = requests.get(url, params=params)
# print("response.url:", response.url)

# POST - add(mode: one)
# data = {"rest": "add", "mode": "one", "STOCK_ID": "6172", "PRICE": "42.00"}

# POST - add(mode: multi)
# datas = [{"STOCK_ID": "3558", "NAME": "", "PRICE": "423.00"}]
# data = {"rest": "add", "mode": "multi",
#         "datas": json.dumps(datas)}
# response = requests.post(url, data=data)

# POST - update(mode: one)
# data = {"rest": "update", "mode": "one", "GUID": "2b0232374d954a5cb1fcb735045cb20d", "PRICE": "42.00"}
# response = requests.post(url, data=data)

# POST - update(mode: multi)
datas = [{'STOCK_ID': '3458',
          'NAME': 'NTNU1'},
         {'STOCK_ID': '3558',
          'NAME': 'NTNU2'}
         ]
data = {"rest": "update", "mode": "multi",
        "datas": json.dumps(datas)}
response = requests.post(url, data=data)

# POST - delete
# data = {"rest": "delete", "STOCK_ID": "3458"}
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
