compose-up:
	COMPOSE_PROJECT_NAME=zubr-locality \
	HOST_UID=$$(id -u $${USER}):$$(id -g $${USER}) \
	docker-compose -f infrastructure/docker-compose.yml up -d

compose-down:
	COMPOSE_PROJECT_NAME=zubr-locality \
	HOST_UID=$$(id -u $${USER}):$$(id -g $${USER}) \
	docker-compose -f infrastructure/docker-compose.yml \
 	down

compose-composer-ci:
	docker exec --user "$$(id -u):$$(id -g)" locality_php_1 composer install -a --no-dev --no-interaction --no-scripts

compose-up-ci:
	COMPOSE_PROJECT_NAME=locality \
	docker-compose -f infrastructure/docker-compose.ci.yml up -d

compose-down-ci:
	COMPOSE_PROJECT_NAME=locality \
	docker-compose -f infrastructure/docker-compose.ci.yml down

compose-dev-build:
	COMPOSE_PROJECT_NAME=locality \
	docker-compose -f infrastructure/docker-compose.yml build