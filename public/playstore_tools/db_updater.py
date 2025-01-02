import pymysql
from bs4 import BeautifulSoup
import requests
import time
import html
import random
from fake_useragent import UserAgent
from urllib.parse import unquote
from datetime import datetime

def get_app_category_from_playstore(package_name, wait=1):
    app_name, app_category, thumbnail = '', '', ''
    try:
        print(package_name)
        time.sleep(wait)
        ua = UserAgent()
        # 顯示語言hl設為中文
        response = requests.get(f"https://play.google.com/store/apps/details?id={package_name}&hl=zh_TW&gl=TW",
                               headers={ 'user-agent': ua.chrome})
        if response.status_code != 200:
            if response.status_code == 404:
                app_category = 'UNKNOW_SOURCE'
            else:
                wait = wait + random.randint(1, 10)
                print(f'sleep {wait}')
                app_name, app_category, thumbnail = get_app_category_from_playstore(package_name, wait)
        else:
            soup = BeautifulSoup(response.content, 'html.parser')
            app_name = soup.find('h1').text
            print(app_name)
            thumbnail = soup.find('img', class_='T75of cN0oRe fFmL2e').get('srcset')
            element_a_list = soup.find_all('a', attrs={'class': ['WpHeLc', 'VfPpkd-mRLv6', 'VfPpkd-RLmnJb']})
            for element_a in element_a_list:
                element_a_href = unquote(element_a.get('href'))
                if '/store/apps/category/' in element_a_href:
                    app_category = element_a.get('aria-label') if 'GAME' not in element_a_href.split('/store/apps/category/')[-1] else '遊戲'
                    break
            if app_category == '':
                for element_a in element_a_list:
                    element_a_href = unquote(element_a.get('href')).lower()
                    if '遊戲' in element_a_href and 'q=' in element_a_href:
                        # 重名命Category回傳
                        app_category = '遊戲'
                        break
    except Exception as e:
        print(str(e))
    return app_name, app_category, thumbnail
    
def main():
    # 連線MySQL DB
    conn = pymysql.connect(host='localhost', port=3306, user='zhiren', passwd='QmeDkLo3emHUPeqY', db='app_3rd', charset='utf8')

    # 查UNKNOW
    cursor = conn.cursor(pymysql.cursors.DictCursor)
    cursor.execute("SELECT package_name FROM app_usage WHERE app_category = 'UNKNOW' GROUP BY package_name")
    package_list = list(map(lambda row: row['package_name'], cursor.fetchall()))

    # 查 app_category
    cursor = conn.cursor(pymysql.cursors.DictCursor)
    cursor.execute('SELECT id, package_name FROM app_category')
    db_app_category = {}
    for row in cursor.fetchall():
        db_app_category[row['package_name']] = row['id']

    new_package_list = []
    i = 0
    for package_name in package_list:
        i+=1
        print(f'{i}/{len(package_list)}')
        print(package_name)
        try:
            app_name, app_category, thumbnail = get_app_category_from_playstore(package_name)
            
            print(app_name, app_category, thumbnail)
            app_name = app_name.replace(",", '、')
            app_name = app_name.replace("'", '＇')
            if package_name in db_app_category:
                cursor = conn.cursor(pymysql.cursors.DictCursor)
                cursor.execute(f"INSERT INTO app_category (id, package_name, app_name, category, thumbnail) VALUE (NULL, '{package_name}', '{app_name}', '{app_category}', '{thumbnail}')")
                cursor = conn.cursor(pymysql.cursors.DictCursor)
                cursor.execute(f"UPDATE `app_usage` SET `app_category` = '{app_category}' WHERE `package_name` = '{package_name}'")
                cursor = conn.cursor(pymysql.cursors.DictCursor)
                cursor.execute(f"DELETE FROM `app_category` WHERE `id` = {db_app_category[package_name]}")
            else:
                cursor = conn.cursor(pymysql.cursors.DictCursor)
                cursor.execute(f"INSERT INTO app_category (id, package_name, app_name, category, thumbnail) VALUE (NULL, '{package_name}', '{app_name}', '{app_category}', '{thumbnail}')")
                cursor = conn.cursor(pymysql.cursors.DictCursor)
                cursor.execute(f"UPDATE `app_usage` SET `app_category` = '{app_category}' WHERE `package_name` = '{package_name}'")

            conn.commit()
        except Exception as e:
            print(str(e))
            new_package_list.append(package_name)

if __name__ == '__main__':
    while True:
        print(datetime.now().strftime('%Y-%m-%d %H:%M:%S'))
        main()
        # 休60秒
        time.sleep(60)