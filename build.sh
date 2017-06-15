#!/usr/local/bin/fish
#Author: José Miguel Pérez Ontiveros

mkdir -p projects/$argv[1]/admin
cp -r src/* projects/$argv[1]/admin
printf "<?php\n\t\$conn = new mysqli('"$argv[2]"', '"$argv[3]"', '"$argv[4]"', '"$argv[1]"');\n\tif (\$conn->connect_errno)\n\t\techo 'Failed to connect to MySQL: ('.\$conn->connect_errno.')'.\$conn->connect_error;\n?>" > projects/$argv[1]/admin/db_connection.inc.php
mv temp/$argv[1]/*.php projects/$argv[1]/admin
mv temp/$argv[1]/*.sql projects/$argv[1]
rm -rf temp/$argv[1]
mysql -h $argv[2] -u $argv[3] --password=$argv[4] < projects/$argv[1]/$argv[1].sql

echo 'Project<a href="projects/'$argv[1]'/admin/">' $argv[1] '</a>built successfully.'
