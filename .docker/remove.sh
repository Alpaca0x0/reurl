#!/bin/bash
docker rm reurl-app reurl-db
docker rmi alpaca/reurl
sudo rm /var/www/url/.docker/mysql -r
