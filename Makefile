setup-project:
	@echo "Setting up the project..."
	cp .env .env.dev
	docker-compose up -d --build
	docker exec -it test-task-php-fpm composer install
	docker exec -it test-task-php-fpm php bin/console doctrine:database:create
	docker exec -it test-task-php-fpm php bin/console doctrine:migrations:migrate
	docker exec -it test-task-php-fpm php bin/console app:seed:test-data-seeder
	@echo "Project setup complete."

run-unit-test:
	docker exec -it test-task-php-fpm php bin/phpunit --testsuite Unit

php-fpm-bash:
	docker exec -it test-task-php-fpm bash
