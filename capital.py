from api import CapitalApi
import datetime
from decimal import Decimal, ROUND_HALF_UP

api = CapitalApi()


def updateReEvenu(time, remark, str_total_revenu):
    administrator = "j32u4ukh"
    users = ["ahuayeh"]

    current_time = datetime.datetime.strptime(time, '%Y/%m/%d')
    total_revenu = Decimal(str_total_revenu)

    last_time = current_time - datetime.timedelta(days=1)
    stock_dict = {}
    rate_dict = {}
    total_rate_stock = Decimal("0")
    cum_revenu = Decimal("0")

    results = api.read(user=administrator, end_time=f"{last_time}")
    result = results[0]
    stock = Decimal(result[-1]["STOCK"])
    rate_dict[administrator] = stock
    total_rate_stock += stock

    results = api.read(user=administrator, end_time=f"{current_time}")
    result = results[0]
    stock = Decimal(result[-1]["STOCK"])
    stock_dict[administrator] = stock

    for user in users:
        results = api.read(user=user, end_time=f"{last_time}")
        result = results[0]
        stock = Decimal(result[-1]["STOCK"])
        rate_dict[user] = stock
        total_rate_stock += stock

        results = api.read(user=user, end_time=f"{current_time}")
        result = results[0]
        stock = Decimal(result[-1]["STOCK"])
        stock_dict[user] = stock

    for user in users:
        stock = rate_dict[user]
        rate_dict[user] = stock / total_rate_stock

    stock = rate_dict[administrator]
    rate_dict[administrator] = stock / total_rate_stock

    for user in users:
        print(f"time: {datetime.datetime.strftime(current_time, '%Y-%m-%d')}")
        print(f"user: {user}")
        stock = stock_dict[user]
        rate = rate_dict[user]
        print(f"rate: {rate}")
        user_revenu = (total_revenu * rate).quantize(Decimal('0'), ROUND_HALF_UP)
        cum_revenu += user_revenu
        print(f"revenu: {user_revenu}")
        stock += user_revenu
        print(f"stock: {stock}")
        api.addBuffer(time=datetime.datetime.strftime(current_time, '%Y-%m-%d'),
                      user=user,
                      capital_type=CapitalApi.EType.Revenu,
                      flow=str(user_revenu),
                      stock=str(stock),
                      remark=remark)
        print()

    print(f"time: {datetime.datetime.strftime(current_time, '%Y-%m-%d')}")
    print(f"user: {administrator}")
    stock = stock_dict[administrator]
    user_revenu = total_revenu - cum_revenu
    print(f"revenu: {user_revenu}")
    stock += user_revenu
    print(f"stock: {stock}")
    api.addBuffer(time=datetime.datetime.strftime(current_time, '%Y-%m-%d'),
                  user=administrator,
                  capital_type=CapitalApi.EType.Revenu,
                  flow=str(user_revenu),
                  stock=str(stock),
                  remark=remark)

    api.add()


"""																
"""

# updateReEvenu(time="2021/08/10", remark="00646", str_total_revenu="393")
# updateReEvenu(time="2021/08/10", remark="2390云辰", str_total_revenu="-1168")

# 1310,2021-03-24,2021-04-21,19.05,21.65,1,19070.00,84,2496.00
# 2012,2021-03-23,2021-04-23,19.95,25.10,1,19970.00,95,5232.00
# api.updateDataBuffer(TradeRecordApi.splitRawData("3,1310,2021-03-24,2021-04-21,19.05,21.65,1,19070.00,84,2496.00"))
# api.updateRawDataBuffer("4,2012,2021-03-23,2021-04-23,19.95,25.10,1,19970.00,95,5232.00")

# api.updateBuffer("5", "2012", "2021-03-23", "2021-04-23", "0", "0", "0", "0", "0", "590.0")
# api.updateBuffer("6", "2419", "2021-04-28", "2021-05-03", "25.55", "23.55", "1", "25570.00", "90", "-2110.0")
# api.delete(datas=[4, 5, 6])

results = api.read(mode="tail")

for result in results:
    if len(result) == 1:
        print(result)
    else:
        for idx, res in enumerate(result):
            print(idx, res)
