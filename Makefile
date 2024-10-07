PHP = php
DOCKER_COMPOSE = docker compose
SAIL = sail

init:
	docker compose up -d && docker run --rm \ -u "$(id -u):$(id -g)" \ -v "$(pwd):/var/www/html" \ -w /var/www/html \ laravelsail/php83-composer:latest \ composer install --ignore-platform-reqs

start:
	./vendor/bin/sail up -d

stop:
	./vendor/bin/sail down

app:
	docker compose -f docker-compose.yml exec -u sail laravel.test bash

pint:
	./vendor/bin/pint
