import requests
from bs4 import BeautifulSoup
import urllib
import urllib.request
import urllib.error

url = "https://webcapitalapiinvestment.000webhostapp.com/trade_record.php"
data = {'post_msg': 'This is post_msg3.'}

response = requests.post(url, data=data)

# print(x.text)
# html = BeautifulSoup(response)
html = BeautifulSoup(response.text, 'html.parser')

