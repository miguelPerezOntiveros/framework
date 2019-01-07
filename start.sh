#!/bin/sh

# defaults
web_port=8080
db_port=3306

# check options
while [ ! $# -eq 0 ]
do
	case "$1" in
		--db-port|-db)
			db_port=$2
			shift
			;;
		--web-port|-web)
			web_port=$2
			shift
			;;
		*)
			echo "Usage: ./start.sh [-db|--db-port db_port] [-web|--web-port web_port]"
			exit
	esac
	shift
done

# Check if the PHP port is free
while echo exit | nc localhost $web_port > /dev/null;  do
	echo 'Waiting for port '$web_port' to become available.'
	sleep 1;
done
# Check if something is listening on the DB port
while ! echo exit | nc localhost $db_port > /dev/null;  do
	echo 'DB not available on port '$db_port'. Will restart'
	sudo /usr/local/mysql/support-files/mysql.server restart &
	sleep 5;
done

# Run the PHP server and open a web browser tab to it
printf "<?php\n\t\$db_port = "$db_port";\n?>" > start_settings.inc.php
open http://localhost:$web_port
php -S localhost:$web_port
