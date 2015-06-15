#!/usr/bin/env python
# -*- coding: utf-8 -*-

import argparse
import re
from multiprocessing import Pool
import requests
import bs4
import time
from time import sleep
import xml.etree.ElementTree as ET
import re
import sys
import json
import MySQLdb
import random

#待爬取的urls
acccount_urls=[

'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFty72GGlJl1Fa32fnPqybPV8&eqs=eJs2o1NgdWgsoZFJRezGQul0hrTWQVdFdCB4Dsagqyz2T%2Bz6FSUNa8zK97qTiqrdf%2Fm4i&ekv=4&page=1&t=',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt98u7kmyb9-OpSPghHa7Uiw&eqs=dfsroPXglYvyo2HGAmjLWuVv4Q2jJKpbccC1%2F4i6fPU0ImXKrPYUak6P0VLasvUp%2B%2FJXp&ekv=4&page=1&t=',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFtwpx4WaL2AzuAe1OmSHfB5Q&eqs=SdsSoOCgsGKho5duSruI%2BuDRyVQh1z46KW7eQDkfBpWyNlR4gV11X1PEMi78ZojF7a13A&ekv=4&page=1&t=1434250695417',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt86NKeSGd_BQKp1GcDkYpv0&eqs=A%2BsyoLcgkR8RouvjwSXfcusKGIjEjna4bHimBR%2FkiVzeX%2BIZUhMU6oD5ZRDWeBH7ec2Jp&ekv=4&page=1&t=1434250772825',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt9CaL2pJRKejPFmSWPpIroI&eqs=2AsFoUvgutnfoeE7UybItuEB%2Be2sLRBoHrrLeHvPXuynWpj0he4QxAie72ZUo38Z1mG49&ekv=4&page=1&t=1434308584971',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFtyYfYuoI1iJmzZ_zh3rwVA0&eqs=s4slo1jgXhoMoL1QNFlWIubtBHo7LGr0xkAoIvN1trS8uNJFmmPWMGZomBS7cbsss7HU1&ekv=4&page=1&t=1434308643334',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt455ps1TgT74uilbTe7-2cI&eqs=GTsEodzgtqyRou1Ua9so8udor56%2FrPI7R%2FcphhkYNFBJ2aaC0wo4ThoFg5lO2ZKoUa3w2&ekv=4&page=1&t=1434308679144'

]



#匹配js返回的请求json数据
r_req_data = re.compile(r"sogou.weixin.gzhcb\((.*)\)")
r_mid = re.compile(r"mid=([0-9]+)")



conn= MySQLdb.connect(
        host='localhost',
        port = 3306,
        user='wejoy',
        passwd='wejoy',
        db ='test',
)

def get_account_page_urls():
    all_urls = []
    for acccount_url in acccount_urls:
        acccount_url = acccount_url +  get_current_timestamp()
        all_urls.append(acccount_url)
    #shuffle
    random.shuffle(all_urls)
    return all_urls

def get_current_timestamp():
    return (str)((long)(time.time()*1000))
    
def get_account_data(account_page_url):
    try:
        account_data = {}
        response = requests.get(account_page_url)
        # soup = bs4.BeautifulSoup(response.text)
        req_json = get_regex_value(r_req_data, response.text, 1)
        req_json = req_json.replace('gbk','utf-8')
        req_json = req_json.replace('gb2312','utf-8')
        req_json = req_json.replace('GBK','utf-8')
        item_json = json.loads(req_json)
        for item_xml_data in item_json["items"]:
            root=ET.fromstring(item_xml_data)
            headimage = root.findall(".//headimage")[0].text
            sourcename = root.findall(".//sourcename")[0].text
            openid = root.findall(".//openid")[0].text
            title = root.findall(".//title")[0].text
            url = root.findall(".//url")[0].text
            content168 = root.findall(".//content168")[0].text
            imglink = root.findall(".//imglink")[0].text
            docid = root.findall(".//display/docid")[0].text
            last_modified = root.findall(".//lastModified")[0].text
            
        
            mid  = get_regex_value(r_mid, url, 1)
        
            account_data["headimage"] = headimage
            account_data["sourcename"] = sourcename
            account_data["openid"] = openid
            account_data["title"] = title
            account_data["url"] = url
            account_data["content168"] = content168
            account_data["imglink"] = imglink
            account_data["docid"] = docid
            account_data["last_modified"] = last_modified
            account_data["mid"] = mid
            # print docid
        
            # print item_xml_data
            # print account_data
            insert_data(account_data)
            sleep( random.choice(range(12)) )
            print "\r\n"

            # return account_data
    except:
            print("get_account_data Unexpected error:", sys.exc_info()[0])
            # return ''

def parse_args():
    parser = argparse.ArgumentParser(description='Scrawler wx account data.')
    parser.add_argument('--workers', type=int, default=1, help='number of workers to use, 8 by default.')
    return parser.parse_args()

def start_tasks(options):
     account_page_urls = get_account_page_urls()
    # pool = Pool(options.workers)
    # pool.map(get_account_data, account_page_urls)
    
    #single thread
     while True:
        for account_page_url in account_page_urls:
            get_account_data(account_page_url)
        
        #随机一段时间，重新抓取
        sleep(random.randint(6,15)*60*60)
    
def get_regex_value(regex, html, index):
    try:
        return regex.search(html).group(index)
    except:
        print("get_regex_value Unexpected error:", sys.exc_info()[0])
        return ''
        
        
def insert_data(account_data):
    cur = conn.cursor()

    #插入一条数据
    sql = "insert into wx_reader(mid, openid, sourcename,headimage,title,url,content168,imglink,created_at,docid,last_modified) values("
    sql = sql + (account_data["mid"]) + ", "
    sql = sql +'\''+ account_data["openid"] + "\', "
    sql = sql +'\''+ account_data["sourcename"] + "\', "
    sql = sql +'\''+ account_data["headimage"] + "\', "
    sql = sql +'\''+ account_data["title"] + "\', "
    sql = sql +'\''+ account_data["url"] + "\', "
    sql = sql +'\''+ account_data["content168"] + "\', "
    sql = sql + '\''+account_data["imglink"] + "\', "
    sql = sql + get_current_timestamp() + ", "
    sql = sql + '\''+account_data["docid"] + "\', "
    sql = sql + (account_data["last_modified"]) + ", "
    sql = sql[0:len(sql)-2]
    sql = sql + ") on duplicate key update created_at=" + get_current_timestamp()
    sql = sql + ", last_modified=" + (account_data["last_modified"])
    
    print sql
    cur.execute(sql)

    cur.close()
    conn.commit()

if __name__ == '__main__':
    reload(sys)
    sys.setdefaultencoding('utf-8')
    start_tasks(parse_args())
