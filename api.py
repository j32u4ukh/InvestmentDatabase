import json
from pprint import pprint
from abc import ABCMeta, abstractmethod
import requests
from bs4 import BeautifulSoup
from enum import Enum


class InvestmentApi(metaclass=ABCMeta):
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

    @staticmethod
    @abstractmethod
    def splitRawData(raw_data: str) -> dict:
        pass

    def add(self):
        results = self.addData(self.add_buffer)
        self.add_buffer = []

        return results

    def addData(self, datas) -> list:
        data = {"rest": "add", "mode": "multi", "datas": json.dumps(datas)}
        response = requests.post(self.endpoint, data=data)
        return self.execute(response)

    def addDataBuffer(self, data) -> None:
        self.add_buffer.append(data)

    def addRawDataBuffer(self, raw_data: str) -> None:
        data = self.splitRawData(raw_data)
        self.add_buffer.append(data)

    def read(self, datas) -> list:
        response = requests.get(self.endpoint, params=datas)
        return self.execute(response)

    def update(self):
        results = self.updateData(self.update_buffer)
        self.update_buffer = []

        return results

    def updateData(self, datas) -> list:
        data = {"rest": "update", "mode": "multi", "datas": json.dumps(datas)}
        response = requests.post(self.endpoint, data=data)
        return self.execute(response)

    def updateDataBuffer(self, data) -> None:
        self.update_buffer.append(data)

    # 所有欄目都必須有
    def updateRawDataBuffer(self, raw_data: str) -> None:
        data = self.splitRawData(raw_data)
        self.update_buffer.append(data)

    def delete(self):
        results = []

        for data in self.delete_buffer:
            response = requests.post(self.endpoint, data=data)
            result = self.execute(response)
            results.append(result)

        return results

    def deleteDataBuffer(self, data) -> None:
        self.delete_buffer.append(data)

    @abstractmethod
    def deleteBuffer(self, key):
        pass


class TradeRecordApi(InvestmentApi):
    def __init__(self):
        super().__init__(endpoint="https://webcapitalapiinvestment.000webhostapp.com/trade_records/")

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

    def read(self, mode="all", limit=None, start_buy=None, end_buy=None, start_sell=None, end_sell=None):
        datas = {"mode": mode}
        self.formDatas(datas, "limit", limit)
        self.formDatas(datas, "start_buy", start_buy)
        self.formDatas(datas, "end_buy", end_buy)
        self.formDatas(datas, "start_sell", start_sell)
        self.formDatas(datas, "end_sell", end_sell)

        print(datas)

        return super().read(datas)

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

    def deleteBuffer(self, key):
        buffer_data = {"rest": "delete", "NUMBER": str(key)}
        self.deleteDataBuffer(buffer_data)


# TODO: 盡可能使用複合鍵，目前的 primary key 不夠穩健
class CapitalApi(InvestmentApi):
    class EType(Enum):
        NoneTpye = None
        Capital = "capital"
        Revenu = "revenu"

    def __init__(self):
        super().__init__(endpoint="https://webcapitalapiinvestment.000webhostapp.com/capitals/")

    @staticmethod
    def splitRawData(raw_data: str) -> dict:
        split_data = raw_data.split(",")
        data = {"TIME": str(split_data[0]),
                "USER": str(split_data[1]),
                "TYPE": str(split_data[2]),
                "FLOW": str(split_data[3]),
                "STOCK": str(split_data[4]),
                "REMARK": str(split_data[5])}

        return data

    def addBuffer(self, time: str, user: str, capital_type: EType, flow: str, stock: str, remark: str) -> None:
        data = {"TIME": time,
                "USER": user,
                "TYPE": capital_type.value,
                "FLOW": flow,
                "STOCK": stock,
                "REMARK": remark}
        self.add_buffer.append(data)

    # TODO: 增加 limit
    def read(self, mode="all", limit=None, start_time=None, end_time=None, user=None,
             capital_type: EType = EType.NoneTpye):
        datas = {"mode": mode}
        self.formDatas(datas, "limit", limit)
        self.formDatas(datas, "start_time", start_time)
        self.formDatas(datas, "end_time", end_time)
        self.formDatas(datas, "user", user)
        self.formDatas(datas, "type", capital_type.value)

        print(datas)

        return super().read(datas)

    # 允許部分項目為空
    # TODO: 特化成其他所有欄位作為 where 的組合鍵
    def updateBuffer(self, number: str, time: str = None, user: str = None, capital_type: EType = EType.NoneTpye,
                     flow: str = None, stock: str = None, remark: str = None) -> None:
        data = {"NUMBER": number}

        self.formDatas(data, "TIME", time)
        self.formDatas(data, "USER", user)
        self.formDatas(data, "TYPE", capital_type.value)
        self.formDatas(data, "FLOW", flow)
        self.formDatas(data, "STOCK", stock)
        self.formDatas(data, "REMARK", remark)

        self.update_buffer.append(data)

    def deleteBuffer(self, key):
        buffer_data = {"rest": "delete", "NUMBER": str(key)}
        self.deleteDataBuffer(buffer_data)


class InventoryApi(InvestmentApi):
    def __init__(self):
        super().__init__(endpoint="https://webcapitalapiinvestment.000webhostapp.com/inventories/")

    @staticmethod
    def splitRawData(raw_data: str) -> dict:
        split_data = raw_data.split(",")
        data = {"GUID": str(split_data[0]),
                "TIME": str(split_data[1]),
                "STOCK_ID": str(split_data[2]),
                "PRICE": str(split_data[3])}

        return data

    def read(self, mode="all", limit=None, start_buy=None, end_buy=None, stocks: list = None):
        datas = {"mode": mode}
        self.formDatas(datas, "limit", limit)
        self.formDatas(datas, "start_buy", start_buy)
        self.formDatas(datas, "end_buy", end_buy)

        if stocks is not None:
            stock_id = ",".join(stocks)
            self.formDatas(datas, "stock_id", stock_id)

        print(datas)

        return super().read(datas)

    def addBuffer(self, guid: str, time: str, stock_id: str, price: str) -> None:
        data = {"GUID": guid,
                "TIME": time,
                "STOCK_ID": stock_id,
                "PRICE": price}
        self.add_buffer.append(data)

    def updateBuffer(self, guid: str, time: str = None, stock_id: str = None, price: str = None) -> None:
        data = {"GUID": guid}

        self.formDatas(data, "TIME", time)
        self.formDatas(data, "STOCK_ID", stock_id)
        self.formDatas(data, "PRICE", price)

        self.update_buffer.append(data)

    def deleteBuffer(self, key):
        buffer_data = {"rest": "delete", "GUID": str(key)}
        self.deleteDataBuffer(buffer_data)


class StockListApi(InvestmentApi):
    def __init__(self):
        super().__init__(endpoint="https://webcapitalapiinvestment.000webhostapp.com/stock_lists/")

    @staticmethod
    def splitRawData(raw_data: str) -> dict:
        split_data = raw_data.split(",")
        data = {"STOCK_ID": str(split_data[0]),
                "NAME": str(split_data[1]),
                "PRICE": str(split_data[3])}

        return data

    def read(self, mode="all", limit=None, min_price=None, max_price=None, positive_order=True):
        datas = {"mode": mode}
        self.formDatas(datas, "limit", limit)
        self.formDatas(datas, "min", min_price)
        self.formDatas(datas, "max", max_price)

        if positive_order:
            datas["sort"] = "1"
        else:
            datas["sort"] = "0"

        print(datas)

        return super().read(datas)

    def addBuffer(self, stock_id: str, price: str, name: str = "") -> None:
        data = {"STOCK_ID": stock_id,
                "NAME": name,
                "PRICE": price}
        self.add_buffer.append(data)

    def updateBuffer(self, stock_id: str, name: str = None, price: str = None) -> None:
        data = {"STOCK_ID": stock_id}

        self.formDatas(data, "NAME", name)
        self.formDatas(data, "PRICE", price)

        self.update_buffer.append(data)

    def deleteBuffer(self, key):
        buffer_data = {"rest": "delete", "STOCK_ID": str(key)}
        self.deleteDataBuffer(buffer_data)
