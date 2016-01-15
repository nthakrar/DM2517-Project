#!/bin/bash

while read line; do
	url=$(echo "$line" | cut -d, -f3 )
	wget $url
	#echo $url
	#$( echo "$line" | cut -d, -f3 )
done < $1
