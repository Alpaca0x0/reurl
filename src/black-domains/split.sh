#!/bin/bash

input_file="domains.txt"

output_directory="./parts"

rm -r $output_directory.bak 2>/dev/null
cp -r $output_directory $output_directory.bak 2>/dev/null
rm -r $output_directory 2>/dev/null

mkdir -p $output_directory

awk '{ 
  first_char=tolower(substr($0,1,1)); 
  print >> (output_directory first_char ".txt"); 
}' output_directory="$output_directory/" "$input_file"
