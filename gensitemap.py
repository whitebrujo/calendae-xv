#! /usr/bin/python

xml = """<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

"""

baseurl = "http://calendae.ru"
url_tag = ""

for month in range(1, 12):
	for day in range(1, 30):
		url_tag = "<url>\n<loc>" + baseurl + "/" + str(month) + "/" + str(day) + "</loc>\n<lastmod>2015-04-10</lastmod>\n<priority>0.9</priority>\n</url>\n"
		xml = xml + "\n" + url_tag
		

xml = xml + "</urlset>\n</xml>\n"

try:
	f = open('Sitemap', 'w')

	f.write(xml)
	f.close()
	
except IOError:

	print "I/O error.\n";
	quit()
except:

	print "Unknown error.\n"
	quit()
	
print "ok.\n"



