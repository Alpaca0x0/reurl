#!/bin/bash

output_file="domains.txt"

mv $output_file $output_file.bak 2>/dev/null

# from https://cert.pl/en/posts/2020/03/malicious_domains/
curl https://hole.cert.pl/domains/domains.txt --output $output_file