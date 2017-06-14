#!/usr/local/bin/fish
#Author: José Miguel Pérez Ontiveros
mysql -u root --password="x1X48bc0Wsm1.sp25" < projects/$argv[1]/$argv[1].sql.txt
mkdir -p projects/$argv[1]/admin
cp src/* projects/$argv[1]/admin
for x in (seq 5 (count $argv))
	mkdir projects/$argv[1]/admin/$argv[$x]
	cp src/tables/* projects/$argv[1]/admin/$argv[$x]/index.php 
end
echo 'Project<b>' $argv[1] '</b>built successfully.'
