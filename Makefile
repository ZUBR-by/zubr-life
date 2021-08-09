compose-up:
	COMPOSE_PROJECT_NAME=locality \
	HOST_UID=$$(id -u $${USER}):$$(id -g $${USER}) \
	docker-compose -f infrastructure/docker-compose.yml up -d

compose-down:
	COMPOSE_PROJECT_NAME=locality \
	HOST_UID=$$(id -u $${USER}):$$(id -g $${USER}) \
	docker-compose -f infrastructure/docker-compose.yml \
 	down

compose-composer-ci:
	docker exec --user "$$(id -u):$$(id -g)" locality_php_1 composer install -a --no-dev --no-interaction --no-scripts

compose-composer-dev:
	docker exec --user "$$(id -u):$$(id -g)" locality_php_1 \
		composer install --no-interaction --no-scripts

compose-phpunit:
	docker exec --user "$$(id -u):$$(id -g)" locality_php_1 vendor/bin/phpunit --configuration phpunit.xml

compose-up-ci:
	COMPOSE_PROJECT_NAME=locality \
	HOST_UID=$$(id -u $${USER}):$$(id -g $${USER}) \
	docker-compose -f infrastructure/docker-compose.ci.yml up -d --build

compose-down-ci:
	COMPOSE_PROJECT_NAME=locality \
	HOST_UID=$$(id -u $${USER}):$$(id -g $${USER}) \
	docker-compose -f infrastructure/docker-compose.ci.yml down

compose-dev-build:
	COMPOSE_PROJECT_NAME=locality \
	HOST_UID=$$(id -u $${USER}):$$(id -g $${USER}) \
	docker-compose -f infrastructure/docker-compose.yml build

phpunit:
	php8.0 api/vendor/bin/phpunit --configuration api/phpunit.xml

psalm:
	php8.0 api/vendor/bin/psalm --show-info=true -c infrastructure/psalm.xml
