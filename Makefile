
test:
	phpunit --color tests/

install_phpunit:
	wget https://phar.phpunit.de/phpunit.phar
	chmod +x phpunit.phar
	sudo mv phpunit.phar /usr/local/bin/phpunit
	phpunit --version
