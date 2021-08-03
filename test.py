import requests
from bs4 import BeautifulSoup
import urllib
import urllib.request
import urllib.error
import json

url = "https://webcapitalapiinvestment.000webhostapp.com/trade_records"

# GET
params = {"read": "head", "limit": "3"}
response = requests.get(url, params=params)
print("response.url:", response.url)

if response.status_code == requests.codes.ok:
    print(f"[{response.status_code}] OK")

# data = {'post_msg': 'This is post_msg3.'}
# response = requests.post(url, data=data)

# print(x.text)
# html = BeautifulSoup(response)
html = BeautifulSoup(response.text, 'html.parser')
results = json.loads(html.p.text)
print(results)
