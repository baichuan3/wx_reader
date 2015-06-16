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
import traceback

#待爬取的urls
acccount_urls=[

'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFty72GGlJl1Fa32fnPqybPV8&eqs=eJs2o1NgdWgsoZFJRezGQul0hrTWQVdFdCB4Dsagqyz2T%2Bz6FSUNa8zK97qTiqrdf%2Fm4i&ekv=4&page=1&t=',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt98u7kmyb9-OpSPghHa7Uiw&eqs=dfsroPXglYvyo2HGAmjLWuVv4Q2jJKpbccC1%2F4i6fPU0ImXKrPYUak6P0VLasvUp%2B%2FJXp&ekv=4&page=1&t=',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFtwpx4WaL2AzuAe1OmSHfB5Q&eqs=SdsSoOCgsGKho5duSruI%2BuDRyVQh1z46KW7eQDkfBpWyNlR4gV11X1PEMi78ZojF7a13A&ekv=4&page=1&t=1434250695417',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt86NKeSGd_BQKp1GcDkYpv0&eqs=A%2BsyoLcgkR8RouvjwSXfcusKGIjEjna4bHimBR%2FkiVzeX%2BIZUhMU6oD5ZRDWeBH7ec2Jp&ekv=4&page=1&t=1434250772825',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt9CaL2pJRKejPFmSWPpIroI&eqs=2AsFoUvgutnfoeE7UybItuEB%2Be2sLRBoHrrLeHvPXuynWpj0he4QxAie72ZUo38Z1mG49&ekv=4&page=1&t=1434308584971',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFtyYfYuoI1iJmzZ_zh3rwVA0&eqs=s4slo1jgXhoMoL1QNFlWIubtBHo7LGr0xkAoIvN1trS8uNJFmmPWMGZomBS7cbsss7HU1&ekv=4&page=1&t=1434308643334',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt455ps1TgT74uilbTe7-2cI&eqs=GTsEodzgtqyRou1Ua9so8udor56%2FrPI7R%2FcphhkYNFBJ2aaC0wo4ThoFg5lO2ZKoUa3w2&ekv=4&page=1&t=1434308679144',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt9OIEbzwI6cyUmBfCgBRHBU&eqs=dXsGov7g6H1XoN%2Bdbmx6UuMkve3%2FMqo4sP2m7VUmNT5N5gbx5N2cq5C4tot4XWUdj8EoA&ekv=4&page=1&t=1434351475413',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFtzcbFoqS4ip7ZkUQlz88kXQ&eqs=EZs1o1ngE2z0o53yaeOklu0XJ9wW%2BL66lMSdp1wO0esmQPqUA%2BEaQp8tK6vslVFCkV15F&ekv=4&page=1&t=1434351529698',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt7e7hq42aBOOeOQnFcLvKEM&eqs=OXsDoAcgf%2FAloJczvYP9au2q7CZ4J5L6ixtog93hF4iMQKce3HY6raMEQd62W46N7Js67&ekv=4&page=1&t=1434351591415',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFtz-ov6wZChK8smIQb4EXj_o&eqs=vPsuoH0gCrwVowtGmpyGkujMm7gjyp%2BfAK1pIUfOljfgRYJz0LxZSNFVIHUAu4fU8PjrH&ekv=4&page=1&t=1434351637880',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt1A9eVKH7qBQh5pf5tt5n-4&eqs=5GsMo9AgpwbWo1e%2FhnvA9ujIkUzPsGnswds0d8jbzBWNbVMtmWzFv9LlW1Rlsmw8WyXmr&ekv=4&page=1&t=1434351667848',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFtwHcjPKR_nOreR0yWpEYCh0&eqs=lssAoZAgAH67ofSTe6zQbuWeEbk6NanMJz6e0JmXNRSxAb5e8W3f6A%2BpYVeQbzEsgbi2W&ekv=4&page=1&t=1434351713765',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt-F95xcnWlUw2HLx0dqmArM&eqs=%2BwsaoBNgpR2jo4mChD8CmuLTXztM2is7%2F5lzk3pAXsQXWcM%2F%2BpZye9K56vkMDxQy2tVo3&ekv=4&page=1&t=1434351758168',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt7EVnIjsiOajYQ-jfO9L6QA&eqs=ycs%2BojwgDOUMo4KJ%2BIDxHuwDkVeHsKlm8kJaN2NjksUtyx5OZrtTC1r7aTH2H2eJ3GW67&ekv=4&page=1&t=1434351802511',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFtxG-2J2sGx3l5-pknZDv60g&eqs=68s3o0cgqq9nos3vVQDa7uG7MRxTH22GjsqXCOHytbPvUatX4mv%2F7nY2micOsFO6UoOh3&ekv=4&page=1&t=1434351835046',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt5Bg_S5FCRWYXZl4N_xG9hk&eqs=Z7sXoAKgVObjoz8yJ71nVuUuGyfoWv07os977qfkH0apBfNb%2FhBD%2B6pz9%2BZLQmIQwoMcX&ekv=4&page=1&t=1434351891117',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFtw5HO0UEXqv_gFvTWjE8rl0&eqs=C0sWoRTgCYsGodYSiHuTSudiApMsqlOE7WoxgnNjeh5jGuMZeID0xi%2BjqeZ07XK7fuZX3&ekv=4&page=1&t=1434351919865',
'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&openid=oIWsFt4bY1Ee_9GHdrk2pOgzczyY&eqs=U2sDoPeguRL6otaueLU2QuY64svwH8SY%2FtkGZ5krx9FcJUg4DHlADVZpB2z2JgKOVVLsN&ekv=4&page=1&t=1434352126867'

]



#匹配js返回的请求json数据
r_req_data = re.compile(r"sogou.weixin.gzhcb\((.*)\)")
r_mid = re.compile(r"mid=([0-9]+)")
r_openid = re.compile(r"openid=(.+?)&")



conn= MySQLdb.connect(
        host='localhost',
        port = 3306,
        user='wejoy',
        passwd='wejoy',
        db ='wx_reader',
	charset="utf8"
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
        headers={'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:38.0) Gecko/20100101 Firefox/38.0'}
        response = requests.get(account_page_url, headers=headers)
        # soup = bs4.BeautifulSoup(response.text)
        req_json = get_regex_value(r_req_data, response.text, 1)
        req_json = req_json.replace('gbk','utf-8')
        req_json = req_json.replace('gb2312','utf-8')
        req_json = req_json.replace('GBK','utf-8')
        item_json = json.loads(req_json)
        max_mid = query_one_data(get_regex_value(r_openid, account_page_url, 1))
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
            
            if((long)(mid) <= (long)(max_mid)):
                print sourcename  , '''has no new article, so break'''
                break
        
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
            # sleep( random.choice(range(12)) )
            #anti block
            sleep(60 + random.randint(10,300))
            print "\r\n"

            # return account_data
    except:
            print("get_account_data Unexpected error:", sys.exc_info()[0])
            print("get_account_data Unexpected error: trace ", traceback.format_exc())
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

def query_one_data(openid):
    cur = conn.cursor()

    #获得openid发布最大文章mid
    mid = 0
    sql = "select mid from wx_reader where openid="
    sql = sql +'\''+ openid + "\' order by mid desc limit 1"
    cur.execute(sql)
    value=cur.fetchone()
    if(value):
        mid = value[0]

    cur.close()
    conn.commit()
    
    return mid

def setLogger():  
    # 创建一个logger,可以考虑如何将它封装  
    logger = logging.getLogger('mylogger')  
    logger.setLevel(logging.DEBUG)  
      
    # 创建一个handler，用于写入日志文件  
    fh = logging.FileHandler(os.path.join(os.getcwd(), 'log.txt'))  
    fh.setLevel(logging.DEBUG)  
      
    # 再创建一个handler，用于输出到控制台  
    ch = logging.StreamHandler()  
    ch.setLevel(logging.DEBUG)  
      
    # 定义handler的输出格式  
    formatter = logging.Formatter('%(asctime)s - %(module)s.%(funcName)s.%(lineno)d - %(levelname)s - %(message)s')  
    fh.setFormatter(formatter)  
    ch.setFormatter(formatter)  
      
    # 给logger添加handler  
    logger.addHandler(fh)  
    logger.addHandler(ch)  
      
    # 记录一条日志  
    logger.info('hello world, i\'m log helper in python, may i help you')  
    return logger  

if __name__ == '__main__':
    reload(sys)
    sys.setdefaultencoding('utf8')
    start_tasks(parse_args())
