from api import InventoryApi
import datetime
from decimal import Decimal, ROUND_HALF_UP

api = InventoryApi()

# results = api.read(mode="tail")

# api.addBuffer("2b0232374d954a5cb1fcb735045cb20d", "2021-07-22", "6172", "42.00")
# results = api.add()

#  {'GUID': '2b0232374d954a5cb1fcb735045cb20d', 'TIME': '2021-07-22', 'STOCK_ID': '6172', 'PRICE': '42.00'}
api.updateBuffer("2b0232374d954a5cb1fcb735045cb20d", "2021-07-22", "6172", "42.00")
results = api.update()

# results = api.deleteData("2b0232374d954a5cb1fcb735045cb20d")

for result in results:
    if len(result) == 1:
        print(result)
    else:
        for idx, res in enumerate(result):
            print(idx, res)
