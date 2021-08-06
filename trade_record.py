from api import TradeRecordApi

api = TradeRecordApi()

# api.updateBuffer("3", "1310", "2021-03-24", "2021-04-21", "19.05", "21.65", "1", "19070.00", "84", "2496.00")
# api.updateBuffer("4", "2012", "2021-03-23", "2021-04-23", "19.95", "25.10", "1", "19970.00", "95", "5232.00")

# 1310,2021-03-24,2021-04-21,19.05,21.65,1,19070.00,84,2496.00
# 2012,2021-03-23,2021-04-23,19.95,25.10,1,19970.00,95,5232.00
# api.updateDataBuffer(TradeRecordApi.splitRawData("3,1310,2021-03-24,2021-04-21,19.05,21.65,1,19070.00,84,2496.00"))
# api.updateRawDataBuffer("4,2012,2021-03-23,2021-04-23,19.95,25.10,1,19970.00,95,5232.00")

# api.updateBuffer("5", "2012", "2021-03-23", "2021-04-23", "0", "0", "0", "0", "0", "590.0")
# api.updateBuffer("6", "2419", "2021-04-28", "2021-05-03", "25.55", "23.55", "1", "25570.00", "90", "-2110.0")
# api.delete(datas=[4, 5, 6])

results = api.read()

for result in results:
    if len(result) == 1:
        print(result)
    else:
        for idx, res in enumerate(result):
            print(idx, res)

# api.read(mode="tail", limit=3, start_buy=None, end_buy=None, start_sell=None, end_sell=None)
