import requests
from bs4 import BeautifulSoup
import urllib
import urllib.request
import urllib.error
import json

url = "https://webcapitalapiinvestment.000webhostapp.com/trade_records"

# GET
# params = {"read": "tail", "limit": "3"}
# response = requests.get(url, params=params)
# print("response.url:", response.url)

# POST
data = {'mode': 'one', "stock_id": "2442", "buy_time": "2021-03-23", "sell_time": "2021-05-04",
        "buy_price": "10.80", "sell_price": "11.20", "vol": "1",
        "buy_cost": "10820.0", "sell_cost": "53", "revenue": "327.00"}
response = requests.post(url, data=data)

if response.status_code == requests.codes.ok:
    print(f"[{response.status_code}] OK")
    html = BeautifulSoup(response.text, 'html.parser')
    results = json.loads(html.p.text)
    print(results)
