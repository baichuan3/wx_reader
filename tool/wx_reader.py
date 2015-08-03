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
import sys
import json
import MySQLdb
import random
import traceback
import base64
from Crypto.Cipher import AES
from requests.compat import bytes, str
from urllib import quote

#待爬取的urls

#UserAgent集合，变换ua，减少被屏蔽的概率
UA = "Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5"
# cookies = {}

user_agents=[
    'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_8; en-us) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50',
    'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-us) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50',
    'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0;',
    'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)',
    'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0)',
    ' Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)',
    ' Mozilla/5.0 (Macintosh; Intel Mac OS X 10.6; rv:2.0.1) Gecko/20100101 Firefox/4.0.1',
    'Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1',
    'Opera/9.80 (Macintosh; Intel Mac OS X 10.6.8; U; en) Presto/2.8.131 Version/11.11',
    'Opera/9.80 (Windows NT 6.1; U; en) Presto/2.8.131 Version/11.11',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_0) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11',
    'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Maxthon 2.0)',
    'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; TencentTraveler 4.0)',
    'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)',
    'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; The World)',
    'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SE 2.X MetaSr 1.0; SE 2.X MetaSr 1.0; .NET CLR 2.0.50727; SE 2.X MetaSr 1.0)',
    'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; 360SE)',
    'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Avant Browser)',
    'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1)',
    'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5',
    'Mozilla/5.0 (iPod; U; CPU iPhone OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5',
    'Mozilla/5.0 (iPad; U; CPU OS 4_3_3 like Mac OS X; en-us) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8J2 Safari/6533.18.5',
    'Mozilla/5.0 (Linux; U; Android 2.3.7; en-us; Nexus One Build/FRF91) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
    'MQQBrowser/26 Mozilla/5.0 (Linux; U; Android 2.3.7; zh-cn; MB200 Build/GRJ22; CyanogenMod-7) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
    'Opera/9.80 (Android 2.3.4; Linux; Opera Mobi/build-1107180945; U; en-GB) Presto/2.8.149 Version/11.10',
    'Mozilla/5.0 (Linux; U; Android 3.0; en-us; Xoom Build/HRI39) AppleWebKit/534.13 (KHTML, like Gecko) Version/4.0 Safari/534.13',
    'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.337 Mobile Safari/534.1+',
    'Mozilla/5.0 (hp-tablet; Linux; hpwOS/3.0.0; U; en-US) AppleWebKit/534.6 (KHTML, like Gecko) wOSBrowser/233.70 Safari/534.6 TouchPad/1.0',
    'Mozilla/5.0 (SymbianOS/9.4; Series60/5.0 NokiaN97-1/20.0.019; Profile/MIDP-2.1 Configuration/CLDC-1.1) AppleWebKit/525 (KHTML, like Gecko) BrowserNG/7.1.18124',
    'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; HTC; Titan)',
    'UCWEB7.0.2.37/28/999',
    'NOKIA5700/ UCWEB7.0.2.37/28/999',
    'Openwave/ UCWEB7.0.2.37/28/999',
    'Mozilla/4.0 (compatible; MSIE 6.0; ) Opera/UCWEB7.0.2.37/28/999'

]

#匹配js返回的请求json数据
r_req_data = re.compile(r"sogou.weixin.gzhcb\((.*)\)")
r_mid = re.compile(r"mid=([0-9]+)")
r_openid = re.compile(r"openid=(.+?)&")
SITE_BASE = 'http://weixin.sogou.com/gzh?openid='
INDEX_BASE = 'http://weixin.sogou.com/gzhjs?cb=sogou.weixin.gzhcb&page=1&openid='


conn= MySQLdb.connect(
        host='localhost',
        port = 3306,
        user='wejoy',
        passwd='wejoy',
        db ='wx_reader',
	charset='utf8'
)

# logger = get_logger()

def get_account_page_urls():
    all_urls = []
    file = open("openids.txt")
    for line in file.xreadlines():
        # acccount_url = line +  get_current_timestamp()
        acccount_url = line.strip('\n').strip('\r\n')
        # acccount_url = acccount_url.strip('\r\n')
        all_urls.append(acccount_url)
    #shuffle
    random.shuffle(all_urls)
    return all_urls

def get_current_timestamp():
    return (str)((long)(time.time()*1000))

def format_current_timestamp():
    return time.strftime("%Y-%m-%d %H:%M:%S",time.localtime())

def get_account_data(openid):
    try:
        account_data = {}
        #headers={'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.10; rv:38.0) Gecko/20100101 Firefox/38.0'}
        r_headers = {}
        r_headers['User-Agent'] = random.choice(user_agents)
        # print headers

        # proxyDict = {"http":"183.207.228.11:86"}
        # proxyDict = {"http":"183.22.131.107:8090"}
        site_url = SITE_BASE + openid
        # cookies = update_cookies(r_headers['User-Agent'], site_url)
        # resp = requests.get(site_url, headers=r_headers, proxies=proxyDict)
        cookies = {}
        s = requests.Session()
        s.headers.update(r_headers)
        resp = s.get(site_url, headers=r_headers)
        # print resp.text
        # headers = {"User-Agent": ua}
        # url = 'http://weixin.sogou.com' + '/weixin?query=123'
        # r = s.get(url)
        # print s.cookies;
        if 'SNUID' not in s.cookies:
            p = re.compile(r'(?<=SNUID=)\w+')
            s.cookies['SNUID'] = p.findall(r.text)[0]
            suv = ''.join([str(int(time.time()*1000000) + random.randint(0, 1000))])
            s.cookies['SUV'] = suv

        cookies = s.cookies;


        pattern = (
            r'SogouEncrypt.setKv\("(\w+)","(\d)"\)'
            r'.*?'
            # r'SogouEncrypt.encryptquery\("(\w+)","(\w+)"\)'
            r'SogouEncrypt.encryptquery\("([\w-]+)[\s\S]?","(\w+)"\)'
        )
        m = re.findall(pattern, resp.text, re.S)
        # print m
        key, level, secret, setting = m[0]

        # index_url = INDEX_BASE + openid
        eqs = quote(_cipher_eqs(key, secret, setting))
        # print eqs
        # print level

        sleep(20 + random.randint(10,60))
        account_page_url = INDEX_BASE + openid + "&eqs=" + eqs + "&ekv=" + level +  "&t=" +  get_current_timestamp()
        # print account_page_url
        # resp = requests.get(
        #     'http://weixin.sogou.com/gzhjs',
        #     params={
        #         'cb': 'sogou.weixin.gzhcb',
        #         'openid': openid,
        #         'eqs': eqs,
        #         'ekv': level,
        #         'page': '1',
        #         't': get_current_timestamp(),
        #     },
        #     headers=r_headers,
        # )

        # resp = requests.get(account_page_url, headers=r_headers, proxies=proxyDict)
        resp = s.get(account_page_url, headers=r_headers, cookies=cookies)
        # soup = bs4.BeautifulSoup(response.text)
        # print resp.text
        req_json = get_regex_value(r_req_data, resp.text, 1)
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
                print format_current_timestamp() + " " + sourcename  , '''has no new article, so break'''
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
            sleep( 2 + random.choice(range(12)) )
            #anti block
            # sleep(60 + random.randint(10,300))
            print "\r\n"

            # return account_data
    except:
            print(format_current_timestamp() + " " + "get_account_data Unexpected error, openid=" + openid + ", " , sys.exc_info()[0])
            print(format_current_timestamp() + " " + "get_account_data Unexpected error, trace openid=" + openid + ", " , traceback.format_exc())
            # return ''

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

    print format_current_timestamp() + " " + sql
    # cur.execute(sql)

    cur.execute ("""insert into wx_reader(mid, openid, sourcename,headimage,title,url,content168,imglink,created_at,docid,last_modified) values(
        %s, %s, %s, %s, %s, %s, %s, %s,%s, %s,%s) on duplicate key update created_at=%s, last_modified=%s
           """, (account_data["mid"], account_data["openid"], account_data["sourcename"], account_data["headimage"], account_data["title"], account_data["url"],
          account_data["content168"],account_data["imglink"],get_current_timestamp(),account_data["docid"],account_data["last_modified"],get_current_timestamp(),account_data["last_modified"] ))

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

def get_regex_value(regex, html, index):
    try:
        return regex.search(html).group(index)
    except:
        print(format_current_timestamp() + " " + "get_regex_value Unexpected error:", sys.exc_info()[0])
        return ''

def _cipher_eqs(key, secret, setting='sogou'):
    assert len(key) == 11

    ss = setting.split('-')

    # function g
    if len(ss) > 2:
        h = ss[2]
    else:
        h = ss[0]

    # function f
    if len(h) > 5:
        n = h[:-5]
    else:
        n = h + (5 - len(h)) * 's'

    key += n

    data = secret + 'hdq=' + setting
    # padding data
    length = 16 - (len(data) % 16)
    data += chr(length) * length

    IV = b'0000000000000000'
    cipher = AES.new(to_bytes(key), AES.MODE_CBC, IV)
    # encrypt data
    data = cipher.encrypt(to_bytes(data))
    data = to_unicode(base64.b64encode(data))

    # function e
    rv = ''
    i = 0
    for m in range(len(data)):
        rv += data[m]
        if (m == pow(2, i)) and i < 5:
            rv += n[i]
            i += 1
    return rv

def to_unicode(text):
    if isinstance(text, str):
        return text
    return text.decode('utf-8')


def to_bytes(text):
    if isinstance(text, bytes):
        return text
    return text.encode('utf-8')

def update_cookies(ua, url):
    s = requests.Session()
    headers = {"User-Agent": ua}
    s.headers.update(headers)
    # url = 'http://weixin.sogou.com' + '/weixin?query=123'
    r = s.get(url)
    if 'SNUID' not in s.cookies:
        p = re.compile(r'(?<=SNUID=)\w+')
        s.cookies['SNUID'] = p.findall(r.text)[0]
        suv = ''.join([str(int(time.time()*1000000) + random.randint(0, 1000))])
        s.cookies['SUV'] = suv
    return s.cookies

def get_logger():
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

def parse_args():
    parser = argparse.ArgumentParser(description='Scrawler wx account data.')
    parser.add_argument('--workers', type=int, default=1, help='number of workers to use, 8 by default.')
    return parser.parse_args()

def start_tasks(options):
     # account_page_urls = get_account_page_urls()
    # pool = Pool(options.workers)
    # pool.map(get_account_data, account_page_urls)

    #single thread
     while True:
        account_page_urls = get_account_page_urls()
        for account_page_url in account_page_urls:
            get_account_data(account_page_url)
            #anti block
            sleep(100 + random.randint(40,300))

        #随机一段时间，重新抓取
        #mysql 连接超过8小时不使用，会报异常mysql server has gone
        #anti block
        sleep(random.randint(3,7)*60*60)

        #update cookie
        cookies = update_cookies()

if __name__ == '__main__':
    reload(sys)
    sys.setdefaultencoding('utf8')
    start_tasks(parse_args())
