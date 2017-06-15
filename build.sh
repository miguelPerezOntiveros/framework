#!/usr/local/bin/fish
#Author: José Miguel Pérez Ontiveros

mkdir -p projects/$argv[1]/admin
cp -r src/* projects/$argv[1]/admin
mv temp/$argv[1]/*.sql projects/$argv[1]
mv temp/$argv[1]/*.php projects/$argv[1]/admin
rm -rf temp/$argv[1]
mysql -h $argv[2] -u $argv[3] --password=$argv[4] < projects/$argv[1]/$argv[1].sql.txt

echo 'Project<a href="projects/'$argv[1]'/admin/">' $argv[1] '</a>built successfully.'
