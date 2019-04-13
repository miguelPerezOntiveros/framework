#!/bin/sh

# defaults
db_user="root"
db_pass="admin"
db_host="127.0.0.1"
db_port=3306
web_port=80
dev_mode=false

atLeast2Args () {
	if [ $1 -lt 2 ]; then
	    echo "Option '"$2"' requires an argument"
		exit 1    
	fi
}

# check options
while [ ! $# -eq 0 ]
do
	case "$1" in
		--db-user|-u)
			atLeast2Args $# $1
			db_user=$2
			shift
			;;
		--db-pass|-P)
			atLeast2Args $# $1
			db_pass=$2
			shift
			;;
		--db-host|-h)
			atLeast2Args $# $1
			db_host=$2
			shift
			;;
		--db-port|-p)
			atLeast2Args $# $1
			db_port=$2
			shift
			;;
		--web-port|-web)
			atLeast2Args $# $1
			web_port=$2
			shift
			;;
		--dev-mode)
			dev_mode=true
			shift
			;;
		*)
			echo "Usage: ./start.sh [--db-user|-u db_user][--db-pass|-P db_pass][--db-host|-h db_host][--db-port|-p db_port][-web|--web-port web_port]"
			exit
	esac
	shift
done

# Check if something is listening on the DB port
while ! echo exit | nc localhost $db_port > /dev/null;  do
	echo 'DB not available on port '$db_port
	# sudo /usr/local/mysql/support-files/mysql.server restart &
	sleep 5;
done

# Run the PHP server and open a web browser tab to it
printf "<?php\n\t\$db_user = '"$db_user"';\n\t\$db_pass = '"$db_pass"';\n\t\$db_host = '"$db_host"';\n\t\$db_port = '"$db_port"';\n?>" > start_settings.inc.php
if [ "$dev_mode" = true ]; then
	# Check if the PHP port is free
	while echo exit | nc localhost $web_port > /dev/null;  do
		echo 'Waiting for port '$web_port' to become available.'
		sleep 1;
	done

	open http://localhost:$web_port
	sudo php -S 0.0.0.0:$web_port
fi
