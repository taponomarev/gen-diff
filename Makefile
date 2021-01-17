install:
	composer install

validate:
	composer validate

autoload:
	composer dump-autoload

lint:
	composer run-script phpcs -- --standard=PSR12 src bin --standard=PSR12 tests bin

tests:
	composer exec --verbose phpunit tests

tests-coverage:
	composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

.PHONY: tests