from api import DayOhlcApi
import datetime
from decimal import Decimal, ROUND_HALF_UP

api = DayOhlcApi(stock_id="00646")

# results = api.read(mode="all")

# api.addBuffer(time="2021-01-03", open_price="17", high_price="20", low_price="15", close_price="19", volumn="250")
# api.addBuffer(time="2021-01-04", open_price="19", high_price="22", low_price="17", close_price="21", volumn="330")
# results = api.add()

api.updateBuffer(time="2021-01-03", open_price="16", volumn="340")
api.updateBuffer(time="2021-01-04", open_price="18", volumn="355")
results = api.update()

# api.deleteBuffer(key="1218")
# api.deleteBuffer(key="1318")
# results = api.delete()

for result in results:
    if len(result) == 1:
        print(result)
    else:
        for idx, res in enumerate(result):
            print(idx, res)
