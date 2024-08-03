PHP = php
DOCKER_COMPOSE = docker-compose

start:
	./vendor/bin/sail up

stop:
	./vendor/bin/sail down

app:
	docker compose -f docker-compose.yml exec -u sail laravel.test bash

pint:
	./vendor/bin/pint
