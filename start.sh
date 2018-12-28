#!/bin/sh

# set PHP server port
web_port=8080
if ! [ $# -eq 0 ]
then
	web_port=$1
fi
# set DB server port
db_port=3306
if [ $# -gt 1 ]
then
	db_port=$2
fi

# Check if the PHP port is free
while echo exit | nc localhost $web_port;  do
	echo 'Waiting for port '$web_port' to become available.'
	sleep 1;
done
# Check if something is listening on the DB port
while ! echo exit | nc localhost $db_port;  do
	echo 'Waiting for DB to become active on port '$db_port'.'
	sudo /usr/local/mysql/support-files/mysql.server restart &
	sleep 5;
done

# Run the PHP server and open a web browser tab to it
open http://localhost:$web_port
php -S localhost:$web_port
