#!/bin/sh

# defaults
if [ -z ${db_user+x} ]; then db_user="root"; fi
if [ -z ${db_pass+x} ]; then db_pass="admin"; fi
if [ -z ${db_host+x} ]; then db_host="127.0.0.1"; fi
if [ -z ${db_port+x} ]; then db_port="3306"; fi
if [ -z ${web_port+x} ]; then web_port="80"; fi
if [ -z ${prod_mode+x} ]; then prod_mode="false"; fi

atLeast2Args () {
	if [ $1 -lt 2 ]; then
	    echo "Option '"$2"' requires an argument"
		exit 1    
	fi
}

# check options
echo $#' options total'
while [ ! $# -eq 0 ]
do
	echo 'parsing '$1
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
		--db-port|-dbp)
			atLeast2Args $# $1
			db_port=$2
			shift
			;;
		--web-port|-wp)
			atLeast2Args $# $1
			web_port=$2
			shift
			;;
		--prod-mode)
			prod_mode=true
			shift
			;;
		*)
			echo "Usage: ./start.sh [--db-user|-u db_user][--db-pass|-P db_pass][--db-host|-h db_host][--db-port|-dbp db_port][--web-port|-wp web_port][--prod-mode]"
			exit
	esac
	shift
done

# Check if something is listening on the DB port
while ! echo exit | nc -w 5 $db_host $db_port > /dev/null;  do
	echo 'DB not available on '$db_host':'$db_port
	# sudo /usr/local/mysql/support-files/mysql.server restart &
	sleep 2;
done
echo 'DB instance available'

sed -i 's/\(;*\)\(.*\)nobody/\2nginx/' /etc/php7/php-fpm.d/www.conf
sed -i 's@listen = 127.0.0.1:9000@listen = /var/run/php7-fpm.sock@g' /etc/php7/php-fpm.d/www.conf
sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g' /etc/php7/php.ini
sed -i 's/80/'"$web_port"'/g' /etc/nginx/conf.d/default.conf

cd /usr/share/nginx
echo "prod_mode is set to: "$prod_mode
if [ "$prod_mode" = "true" ]; then
	rm -rf html
	git clone https://github.com/miguelPerezOntiveros/framework.git html
fi
cd html/web
printf "<?php\n\t\$db_user = '"$db_user"';\n\t\$db_pass = '"$db_pass"';\n\t\$db_host = '"$db_host"';\n\t\$db_port = '"$db_port"';\n?>" > start_settings.inc.php

echo 'setting up maker_mike DB'
cat projects/maker_mike/maker_mike.sql | mysql -h $db_host -u $db_user --password=$db_pass

echo 'setting up maker_mike project'
ln -s /usr/share/nginx/html/web/src/maker_mike.home.php /usr/share/nginx/html/web/projects/maker_mike/admin/home.php

nginx
php-fpm7

echo 'entry.sh reports nginx should now be listening on port '$web_port
cat /etc/nginx/conf.d/default.conf

while true
do
	date
	top -bn 1
	sleep 60
done