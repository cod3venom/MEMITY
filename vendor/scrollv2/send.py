import requests
import os
import time
from static import *

class Send_todb:
    def image_name(self,img):
        if img is not None:
            if '/' in img:
                count = img.count('/')
                img = img.split('/')[count]
        return img
    

    def cache(self,file):
        name = memedir+self.image_name(file)
        _bin = requests.get(file, stream = True)
        _sz = int(_bin.headers['content-length'])
        with open(name,"wb") as _w:
            _w.write(_bin.content)
            print("[+] DOWNLOADED -> " + str(_sz) + " KB")
            img = name.split('/')[3]
            self.send_data(img)
    def send_data(self,img):
        data = {"pymachine":"","pyimage":"memes/"+img}
        sender = requests.post(my_db_url,data)
        print(sender.text)
        _progress=False