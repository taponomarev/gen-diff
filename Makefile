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