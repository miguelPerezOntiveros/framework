#!/bin/sh

nginx
php-fpm7

rc-service mariadb start
mysqladmin -u root password "$DB_ROOT_PASS"

echo "DELETE FROM mysql.user WHERE User='$DB_USER';" > /tmp/sql
echo "GRANT ALL ON *.* TO $DB_USER@'127.0.0.1' IDENTIFIED BY '$DB_PASS' WITH GRANT OPTION;" >> /tmp/sql
echo "GRANT ALL ON *.* TO $DB_USER@'localhost' IDENTIFIED BY '$DB_PASS' WITH GRANT OPTION;" >> /tmp/sql
echo "GRANT ALL ON *.* TO $DB_USER@'::1' IDENTIFIED BY '$DB_PASS' WITH GRANT OPTION;" >> /tmp/sql
echo "DELETE FROM mysql.user WHERE User='';" >> /tmp/sql
echo "DROP DATABASE IF EXISTS test;" >> /tmp/sql
echo "FLUSH PRIVILEGES;" >> /tmp/sql
cat /tmp/sql | mysql -u root --password="$DB_ROOT_PASS"

cd /usr/share/nginx/html
cat projects/maker_mike/maker_mike.sql | mysql -u $DB_USER --password=$DB_PASS

printf "<?php\n\t\$db_user = '"$DB_USER"';\n\t\$db_pass = '"$DB_PASS"';\n\t\$db_host = '"127.0.0.1"';\n\t\$db_port = '"3306"';\n?>" > start_settings.inc.php

while true
do
	date
	top -bn 1
	sleep 60
done