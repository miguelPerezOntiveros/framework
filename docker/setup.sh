sed -i 's/\(;*\)\(.*\)nobody/\2nginx/' /etc/php7/php-fpm.d/www.conf
sed -i 's@listen = 127.0.0.1:9000@listen = /var/run/php7-fpm.sock@g' /etc/php7/php-fpm.d/www.conf
sed -i 's/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g' /etc/php7/php.ini

mysql_install_db --user=mysql --datadir=/var/lib/mysql
rc-status
touch /run/openrc/softlevel

sed -i "s|max_allowed_packet\s*=\s*1M|max_allowed_packet = 200M|g" /etc/mysql/my.cnf
sed -i "s|max_allowed_packet\s*=\s*16M|max_allowed_packet = 200M|g" /etc/mysql/my.cnf

cd /usr/share/nginx
rm -rf html
git clone https://github.com/miguelPerezOntiveros/framework.git html

# mysql -u $DB_USER --password=$DB_PASS