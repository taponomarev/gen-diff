install:
	composer install

validate:
	composer validate

autoload:
	composer dump-autoload

lint:
	composer run-script phpcs -- --standard=PSR12 src bin tests
	composer run-script phpstan-src

tests:
	composer exec --verbose phpunit tests

tests-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

.PHONY: tests