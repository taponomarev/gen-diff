install:
	composer install

validate:
	composer validate

autoload:
	composer dump-autoload

gendiff-help:
	bin/gendiff -h

gendiff-version:
	bin/gendiff -v

lint:
	composer run-script phpcs -- --standard=PSR12 src bin

tests:
	composer exec --verbose phpunit tests

tests-coverage:
	export XDEBUG_MODE=coverage; vendor/bin/phpunit --coverage-clover coverage.xml tests

.PHONY: tests