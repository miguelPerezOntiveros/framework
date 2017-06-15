# framework 1.5
//TODO: make this pretty
### Installing PECL yaml
		curl -O http://pear.php.net/go-pear.phar
		sudo php -d detect_unicode=0 go-pear.phar
		sudo pecl channel-update pecl.php.net
		xcode-select --install
		brew install autoconf
		pecl install yaml
		extension=yaml.so >> /private/etc/php.ini
### Running php dev server 
		php -S localhost:8080 or ./start.sh