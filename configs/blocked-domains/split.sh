#!/bin/bash

input_file="domains.txt"

output_directory="./parts"
mkdir -p $output_directory

rm -r $output_directory.updating 2>/dev/null
mkdir -p $output_directory.updating

rm -r $output_directory.bak 2>/dev/null
cp -rT $output_directory $output_directory.bak 2>/dev/null

#awk -F. '!a[$(NF-1)"."$NF]++ {tld = $(NF-1)"."$NF; sub(/^[ \t]+|[ \t]+$/, "", tld); print tld >> "'$output_directory'.updating/"(substr(tld, index(tld,".")+1) ".txt")}' "$input_file"

awk -F'.' '{ print substr($0,1,length($0)-length($NF)-1) >> "'$output_directory'.updating/"$NF".txt" }' "$input_file"

rm -r $output_directory 2>/dev/null
mv -f $output_directory.updating $output_directory 2>/dev/null

