from selenium import webdriver
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as ec
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options
from bs4 import BeautifulSoup as bs4
from lxml import html
from datetime import datetime
from static import *
from send import *
import os
import time


class browser:
    def config_browser(self):
        option = Options()
        option.add_argument('--no-sandbox')
        option.add_argument('--disable-dev-shm-usage')
        option.headless = True
        option.add_argument('--disable-extensions')
        option.add_argument('disable-gpu')
        option.add_argument('--user-agent="Mediapartners-Google"')
        #option.add_argument('window-size=100,100')
        return option

    def start(self,link):
        chrome = webdriver.Chrome(executable_path=r"/usr/local/share/chromedriver", chrome_options=self.config_browser())
        chrome.find_element_by_css_selector('body').send_keys(Keys.CONTROL + Keys.RETURN)
        chrome.get(link)
        chrome.implicitly_wait(10)
        try:
            body = ec.presence_of_element_located((By.id,'main'))
            WebDriverWait(chrome,TimeoutException).untill(body)
        except KeyboardInterrupt:
            print("[-] PY_SCROLL PROCESS WAS STOPPED BY USER")
        finally:
            self.debug("LOADED -> " + link)
            if "9gag" in link:
                chrome.get_screenshot_as_file('t.png')
                chrome.find_element_by_xpath('//*[@id="qc-cmp2-ui"]/div[2]/div/button[2]').click()
                self.debug("AGREEMENT POPUP CLOSED")
                self.scroll_js(link,chrome)
    def scroll_js(self,link,chrome):
        self.debug("PAYLOAD EXECUTED")
        while online:
            chrome.find_element_by_css_selector('body').send_keys(Keys.PAGE_DOWN)
            self.get_source(link,chrome)

    def get_source(self,link,chrome):
        self.extract_xpath(link,chrome,chrome.page_source)

    def extract_xpath(self,link,chrome,source):
        text = chrome.find_elements(By.XPATH , '//article/div/div/div/a/picture/img|//article/div/div/a/div/video/source[1]')
        for intpr in text:
            file = intpr.get_attribute("src") 
            if file not in self.readf():
                self.logf(file)


    def readf(self):
        with open(db,"r") as reader:
            return reader.read()
            
    def logf(self,data):
        with open(db,"a") as handler:
            data = data.replace("None","")
            handler.write(str(data)+"\n")
            #self.debug("FILE -> " + str(data))
            self.debug("FILE -> " + data)
            send = Send_todb()
            send.cache(data)

            
    def debug(self, data):
        now = datetime.now()
        dt_string = now.strftime("%H:%M:%S")
        print("[+] [" + dt_string + "] " + data)
