#!/usr/bin/python
'''
 This script lists parent categories until root of a product

 Date Written: 7 March 2012
 Author: Raymond S. Usbal
'''

from DBconn import DBclass
from Scrap_url import get_url
from deHTML import dehtml
import lxml.html
import codecs

def get_data(divs):
    bio = []

    for div in divs:
        text = lxml.html.tostring(div, pretty_print=True)
        text = text.replace('<br>', "\n")
        content = dehtml(text)

        if content[:5] == 'Born:':

            content_arr = ["", "", ""]
            date_part = False

            for str in content.split(' '):

                if str == " ":
                    continue

                if str in ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']:
                    date_part = True

                elif len(str) == 4 and str.isdigit():
                    date_part = False
                    content_arr[1] = content_arr[1] + " " + str
                    continue

                if date_part:
                    content_arr[1] = content_arr[1] + " " + str

                elif content_arr[1] == "":
                    content_arr[0] = content_arr[0] + " " + str

                else:
                    content_arr[2] = content_arr[2] + " " + str

            if content_arr[0].strip() != 'Born:':
                content_arr[0] = 'Born: <span class="celebrity_name">' + content_arr[0][6:] + '</span>'

            content_arr[1] = '<time>' + content_arr[1] + '</time>'

            content = " ".join(content_arr)

        if len(bio) == 0 and (content[:18] == 'Contribute to IMDb' or content[:6] == 'Trivia'):
            bio.append("")
        else:
            bio.append('<p>' + content + '</p>')

    return bio

dbc = DBclass()

"""
Localhost connection
"""
conn = dbc.connect(host='192.168.1.105', db='clubcaus_c4c', init_command='SET NAMES utf8')
ci = conn.cursor()

"""
Connect to a2hosting: dev.club4causes.com 
If it does not connect, create a tunnel first:
   $ ssh -p7822 clubcaus@dev.club4causes.com -L 3307:localhost:3306
"""
# ci = dbc.connect(host='127.0.0.1', db='clubcaus_c4c', init_command='SET NAMES utf8', port=3307).cursor()

proxy = "http://anonymouse.org/cgi-bin/anon-www.cgi/"
proxy_len = len(proxy)

imdb_src = "http://www.imdb.com/search/name?name="

ci.execute('''SELECT c.id, c.first_name, c.last_name 
    FROM celebrities AS c
    INNER JOIN celebrities_professions AS cp ON cp.celebrity_id = c.id
    INNER JOIN professions AS p ON p.id = cp.profession_id
    WHERE p.field = %s
    ORDER BY 2, 3''', ('Movies'))

dbc = ci.fetchall()

for pid, first_name, last_name in dbc:
    print "%s: %s, %s" % (pid, last_name, first_name)

    root = get_url(imdb_src + first_name + ' ' + last_name)
    el = root.cssselect("table.results tr.even a")         

    if el:
        doc = get_url( el[0].get('href')[proxy_len:] )
        divs = doc.cssselect("span[itemprop=description], div.txt-block")

        bio = get_data(divs)

        if bio:
            if bio[0]:
                result = ci.execute('''UPDATE celebrities SET short_bio = %s, birth_info = %s WHERE `id` = %s''', tuple(bio + [pid]))
                print "both: %s" % result
            else:
                # sql = '''UPDATE celebrities SET birth_info = '%s' WHERE `id` = %s''' % (bio[1], id,)
                # result = ci.execute(sql)
                # print sql
                result = ci.execute('''UPDATE celebrities SET birth_info = %s WHERE `id` = %s''', (bio[1], pid,))
                print "birth_info: %s" % result

            conn.commit()

    print

# eof
