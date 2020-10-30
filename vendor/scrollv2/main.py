import os
from browser import browser
from static import *


def _config():
    if os.path.isdir(memedir) == False:
        os.mkdir(memedir)
    if os.path.isfile(db) == False:
        with open(db, "w") as writer:
            writer.write("")
            writer.close()


def run():
    print(logo)
    _config()
    _browser = browser()
    for url in link:
        _browser.start(url)
        print(url)


if __name__ == "__main__":
    try:
        run()
    except KeyboardInterrupt:
        print("[-] PY_SCROLL PROCESS WAS STOPPED BY USER")
