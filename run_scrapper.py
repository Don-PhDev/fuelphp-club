#!/usr/bin/python

"""
This script parses HTML of celebrities
  to output FuelPHP migration rows
"""

from os import walk
import lxml.html
import codecs

path_to_docs = "/home/raymond/workarea/club4causes/HTML.celebrities/html/"
output_fuelphp_inserts = "./output_fuelphp_inserts.php"

f = []
for (dirpath, dirname, filenames) in walk(path_to_docs):
    f.extend(filenames)
    break

database = []

print "Processing HTML files..."

for fle in filenames:

    """
    Process another file
    """
    # print '/home/raymond/workarea/club4causes/HTML.celebrities/html' + fle
    f = codecs.open(path_to_docs + fle, "r", "utf-8")
    data = f.read()

    """
    Split data using IMG tag as separator of celebrities
    """
    celebs = data.split('<IMG')
    celebs.pop(0) # Remove first item

    for celeb in celebs:
        # print
        # print "--------------------------------------------"

        """
        Add back the separator and remove white-space
        """
        celeb = '<IMG' + celeb
        celeb = " ".join(celeb.split())

        doc = lxml.html.document_fromstring(celeb)
        sel_img = doc.cssselect('img')
        sel_para = doc.cssselect('p,h1,h2,h3,h4,h5,h6')

        row_name = ""
        row_image = ""
        row_charity = []
        row_cause = []

        for image in sel_img:
            name = image.get("alt")
            if name:
                # print
                # print "[ A. NAME ]" + "\t" + name
                row_name = name
                row_image = image.get("src")

        if row_name == "":
            continue

        nonprofit_type = ""

        for one_para in sel_para:
            hold_print = one_para.text_content().strip()
            hold_begin = hold_print[:6]

            if hold_begin == "Charit":
                nonprofit_type = "charity"
                # print "[ B. CHARITY ]"
            elif hold_begin == "Causes":
                nonprofit_type = "cause"
                # print "[ C. CAUSE ]"
            else:
                more_of_this = hold_print.split(",")
                for more in more_of_this:
                    more = more.strip()
                    if more:
                        # print "\t" + more
                        if nonprofit_type == "charity":
                            row_charity.append(more)
                        elif nonprofit_type == "cause":
                            row_cause.append(more)

        if len(database) > 0:
            """
            Remove Celebrity name from previous Celebrity's Charity/Cause
            """
            if len(database[-1]['cause']) > 0:
                if database[-1]['cause'][-1] == row_name:
                    # print "LAST &: ", database[-1]['cause'][-1]
                    database[-1]['cause'].pop() # remove
            else:
                if database[-1]['charity'][-1] == row_name:
                    # print "LAST 1: ", database[-1]['charity'][-1]
                    database[-1]['charity'].pop() # remove

        database.append({
            'name': row_name,
            'image': row_image,
            'charity': row_charity,
            'cause': row_cause,
        })

    """
    Close this file
    """
    f.close()

"""
Output data
"""
f = codecs.open("FuelPHP.migration.php", "w", "utf-8")
f.write("<?php")

for row in database:
    lname = row['name'].split(" ")
    first_name = lname.pop(0)
    last_name = " ".join(lname)

    f.write("\n")
    f.write("\n\t\t$celebrity_id=\\Model_Celebrity::get_id_autosave(\"" + first_name + "\",\"" + last_name + "\",'" + row['image'] + "');")
    f.write("\n\t\t$ar=\\Model_Celebrity::find($celebrity_id);")

    print
    print row['name']
    print "    " + row['image']

    if len(row['charity']) > 0:
        print "    [ CHARITY ]"
        for charity in row['charity']:
            print "        " + charity
            f.write("\n\t\t$nonprofit_id=\\Model_Nonprofit::get_id_autosave(\"" + charity + "\");")
            f.write("\n\t\t$ar->nonprofits[$nonprofit_id]=\\Model_Nonprofit::find($nonprofit_id);")

    if len(row['cause']) > 0:
        print "    [ CAUSE ]"
        for cause in row['cause']:
            print "        " + cause
            f.write("\n\t\t$cause_id=\\Model_Cause::get_id_autosave(\"" + cause + "\");")
            f.write("\n\t\t$ar->causes[$cause_id]=\\Model_Cause::find($cause_id);")

    f.write("\n\t\t$ar->save();")

f.close()
