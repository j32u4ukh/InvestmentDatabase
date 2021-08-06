import json
from pprint import pprint

import requests
from bs4 import BeautifulSoup


class InvestmentApi:
    def __init__(self, endpoint):
        self.endpoint = endpoint
        self.add_buffer = []
        self.update_buffer = []
        self.delete_buffer = []

    @staticmethod
    def execute(response) -> list:
        if response.status_code == requests.codes.ok:
            print(f"[{response.status_code}] OK")
            html = BeautifulSoup(response.text, 'html.parser')
            p_list = html.find_all("p")

            try:
                results = []

                for p in p_list:
                    if 'api' in p["class"]:
                        result = json.loads(p.text)
                        results.append(result)

                return results

            except json.decoder.JSONDecodeError:
                pprint(html.text)
                return p_list
        else:
            print(f"status_code: {response.status_code}")
            return list()

    @staticmethod
    def formDatas(datas: dict, key, value=None) -> None:
        if value is not None:
            datas[key] = value

    def add(self, datas) -> list:
        data = {"rest": "add", "mode": "multi", "datas": json.dumps(datas)}
        response = requests.post(self.endpoint, data=data)
        return self.execute(response)

    def read(self, datas) -> list:
        response = requests.get(self.endpoint, params=datas)
        return self.execute(response)

    def update(self, datas) -> list:
        data = {"rest": "update", "mode": "multi", "datas": json.dumps(datas)}
        response = requests.post(self.endpoint, data=data)
        return self.execute(response)

    def delete(self, data) -> list:
        response = requests.post(self.endpoint, data=data)
        return self.execute(response)


class TradeRecordApi(InvestmentApi):
    def __init__(self):
        super().__init__(endpoint="https://webcapitalapiinvestment.000webhostapp.com/trade_records/")

    def add(self, datas=None):
        results = super().add(self.add_buffer)
        self.add_buffer = []

        return results

    def addBuffer(self, number, stock_id, buy_time, sell_time, buy_price, sell_price, vol,
                  buy_cost, sell_cost, revenue) -> None:
        data = {"NUMBER": number,
                "STOCK_ID": stock_id,
                "BUY_TIME": buy_time,
                "SELL_TIME": sell_time,
                "BUY_PRICE": buy_price,
                "SELL_PRICE": sell_price,
                "VOL": vol,
                "BUY_COST": buy_cost,
                "SELL_COST": sell_cost,
                "REVENUE": revenue}
        self.add_buffer.append(data)

    def addDataBuffer(self, data) -> None:
        self.add_buffer.append(data)

    def addRawDataBuffer(self, raw_data: str) -> None:
        data = self.splitRawData(raw_data)
        self.add_buffer.append(data)

    @staticmethod
    def splitRawData(raw_data: str) -> dict:
        split_data = raw_data.split(",")
        data = {"NUMBER": str(split_data[0]),
                "STOCK_ID": str(split_data[1]),
                "BUY_TIME": str(split_data[2]),
                "SELL_TIME": str(split_data[3]),
                "BUY_PRICE": str(split_data[4]),
                "SELL_PRICE": str(split_data[5]),
                "VOL": str(split_data[6]),
                "BUY_COST": str(split_data[7]),
                "SELL_COST": str(split_data[8]),
                "REVENUE": str(split_data[9])}

        return data

    def read(self, mode="all", limit=None, start_buy=None, end_buy=None, start_sell=None, end_sell=None):
        datas = {"mode": mode}
        self.formDatas(datas, "limit", limit)
        self.formDatas(datas, "start_buy", start_buy)
        self.formDatas(datas, "end_buy", end_buy)
        self.formDatas(datas, "start_sell", start_sell)
        self.formDatas(datas, "end_sell", end_sell)

        print(datas)

        return super().read(datas)

    def update(self, datas=None):
        results = super().update(self.update_buffer)
        self.update_buffer = []

        return results

    # 允許部分項目為空
    def updateBuffer(self, number, stock_id=None, buy_time=None, sell_time=None, buy_price=None, sell_price=None,
                     vol=None, buy_cost=None, sell_cost=None, revenue=None) -> None:
        data = {"NUMBER": number}

        self.formDatas(data, "STOCK_ID", stock_id)
        self.formDatas(data, "BUY_TIME", buy_time)
        self.formDatas(data, "SELL_TIME", sell_time)
        self.formDatas(data, "BUY_PRICE", buy_price)
        self.formDatas(data, "SELL_PRICE", sell_price)
        self.formDatas(data, "VOL", vol)
        self.formDatas(data, "BUY_COST", buy_cost)
        self.formDatas(data, "SELL_COST", sell_cost)
        self.formDatas(data, "REVENUE", revenue)

        self.update_buffer.append(data)

    def updateDataBuffer(self, data) -> None:
        self.update_buffer.append(data)

    # 所有欄目都必須有
    def updateRawDataBuffer(self, raw_data: str) -> None:
        data = self.splitRawData(raw_data)
        print(f"[TradeRecordApi] updateRawDataBuffer | data: {data}")
        self.update_buffer.append(data)

    def delete(self, datas):
        for data in datas:
            super().delete({"rest": "delete", "NUMBER": str(data)})
