#!/usr/bin/env python
import sys
import os
def main(id_title, title_url):
	id_title_file = open(id_title,"r")
	title_poster_file = open(title_url,"r")
	
	id_title_list=[]
	for line in id_title_file:
		tmp = line.split(",")
		id = tmp[0]
		title = tmp[1]
#		print id + ", " + title
		id_title_list.append({title:id})	

	title_url_list=[]
	for line in title_poster_file:
		tmp=line.split(",")
		title = tmp[0]
		url = tmp[1]
		title_url_list.append({title:url})

	for i in range(len(title_url_list)):
		title = title_url_list[i].keys()[0]
		
		for x in range(len(id_title_list)):
			
			title_inner = id_title_list[x].keys()[0]
		#	print title + ", " + title_inner

			if  title.strip() == title_inner.strip():
					
				id = id_title_list[x].get(title_inner)
				url = title_url_list[i].get(title)
				sys.stdout.write(id + ", " + title + ", " + url)
				break
			
	for i in range(len(title_url_list)):
		title_url_list[i]
#	for x in id_title_list:
#		print id_title_list[x.keys[0]]
		
#		for value in title_url_list[key]:
#			print value
	#	for k in key:

if __name__=='__main__':
	if len(sys.argv)!=3:
		sys.stderr.write("2 files expected as arguments\n")
	else:
		main(sys.argv[1], sys.argv[2])
