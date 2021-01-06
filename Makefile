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
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

.PHONY: tests