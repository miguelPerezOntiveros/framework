#!/bin/sh

rm -r projects/$1
mkdir -p projects/$1/admin/uploads

cd projects/$1/admin
cp -r ../../../src/* .
printf "<?php\n\t\$conn = new mysqli('"$2"', '"$3"', '"$4"', '"$1"');\n\tif (\$conn->connect_errno)\n\t\texit( json_encode((object) ['error' => 'Failed to connect to MySQL: ('.\$conn->connect_errno.')'.\$conn->connect_error]));\n?>" > db_connection.inc.php
printf "<?php\n\tsession_name('"$1"');\n\tsession_start();\n\tif(!isset(\$_SESSION['userName']) && basename(\$_SERVER['PHP_SELF']) != 'login.php'){\n\t\theader('Location: login.php');\n\t\texit();\n\t}\n?>" > session.inc.php

cd uploads
mkdir `echo $5 | sed 's/,/ /g'`