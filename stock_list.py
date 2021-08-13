from api import StockListApi
import datetime
from decimal import Decimal, ROUND_HALF_UP

api = StockListApi()

# results = api.read(mode="all", max_price=200, min_price=70, positive_order=False)

# api.addBuffer("3471", "42.00")
# api.addBuffer("3472", "52.00", name="NTNU3")
# results = api.add()

#  {'GUID': '2b0232374d954a5cb1fcb735045cb20d', 'TIME': '2021-07-22', 'STOCK_ID': '6172', 'PRICE': '42.00'}
# api.updateBuffer("3471", name="NTNU4")
# results = api.update()

api.deleteBuffer(key="1218")
api.deleteBuffer(key="1318")
results = api.delete()

for result in results:
    if len(result) == 1:
        print(result)
    else:
        for idx, res in enumerate(result):
            print(idx, res)
