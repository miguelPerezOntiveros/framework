#!/bin/sh
#Author: José Miguel Pérez Ontiveros

while ! echo exit | nc localhost 8080;  do
	sleep 0.1;
done &&
while ! echo exit | nc localhost 3306;  do
	sleep 0.1;
done && open http://localhost:8080 &
/usr/local/mysql/support-files/mysql.server restart &
php -S localhost:8080
