#!/usr/local/bin/fish
#Author: José Miguel Pérez Ontiveros

mysql -u root --password="x1X48bc0Wsm1.sp25" < projects/$argv[1]/$argv[1].sql.txt
mkdir -p projects/$argv[1]/admin
cp -r src/* projects/$argv[1]/admin

echo 'Project<a href="projects/'$argv[1]'/admin/">' $argv[1] '</a>built successfully.'
