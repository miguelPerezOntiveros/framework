#!/usr/local/bin/fish
#Author: José Miguel Pérez Ontiveros

mysql -h $argv[2] -u $argv[3] --password=$argv[4] < projects/$argv[1]/$argv[1].sql.txt
mkdir -p projects/$argv[1]/admin
cp -r src/* projects/$argv[1]/admin

echo 'Project<a href="projects/'$argv[1]'/admin/">' $argv[1] '</a>built successfully.'
