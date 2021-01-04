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

test:
	composer exec --verbose phpunit tests