import requests
from bs4 import BeautifulSoup
import urllib
import urllib.request
import urllib.error

url = "https://webcapitalapiinvestment.000webhostapp.com/trade_records"

# GET
response = requests.get(url)
print(f"status: {response.status_code}")

if response.status_code == requests.codes.ok:
    print("OK")

# data = {'post_msg': 'This is post_msg3.'}
# response = requests.post(url, data=data)

# print(x.text)
# html = BeautifulSoup(response)
html = BeautifulSoup(response.text, 'html.parser')

