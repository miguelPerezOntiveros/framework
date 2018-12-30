#!/usr/local/bin/fish
#Author: José Miguel Pérez Ontiveros

mkdir -p projects/$argv[1]/admin/uploads
cp -r src/* projects/$argv[1]/admin
printf "<?php\n\t\$conn = new mysqli('"$argv[2]"', '"$argv[3]"', '"$argv[4]"', '"$argv[1]"');\n\tif (\$conn->connect_errno)\n\t\texit( json_encode((object) ['error' => 'Failed to connect to MySQL: ('.\$conn->connect_errno.')'.\$conn->connect_error]));\n?>" > projects/$argv[1]/admin/db_connection.inc.php
printf "<?php\n\tsession_name('"$argv[1]"');\n\tsession_start();\n\tif(!isset(\$_SESSION['userName']) && basename(\$_SERVER['PHP_SELF']) != 'login.php'){\n\t\theader('Location: login.php');\n\t\texit();\n\t}\n?>" > projects/$argv[1]/admin/session.inc.php
mv temp/$argv[1]/*.php projects/$argv[1]/admin
mv temp/$argv[1]/*.* projects/$argv[1]
rm -rf temp/$argv[1]
mysql -h $argv[2] -u $argv[3] --password=$argv[4] < projects/$argv[1]/$argv[1].sql

echo 'Project<a href="projects/'$argv[1]'/admin/">' $argv[1] '</a>built successfully.'
