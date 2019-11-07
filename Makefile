install:
	composer install
test:
	phpunit
run:
	php -S localhost:8000 -t public
logs:
	tail -f storage/logs/lumen.log
