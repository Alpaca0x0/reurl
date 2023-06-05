#!/bin/bash

output_file="domains.txt"

mv $output_file $output_file.bak 2>/dev/null

curl https://hole.cert.pl/domains/domains.txt --output $output_file