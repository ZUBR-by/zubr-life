compose-up:
	COMPOSE_PROJECT_NAME=zubr-locality \
	HOST_UID=$$(id -u $${USER}):$$(id -g $${USER}) \
	docker-compose -f infrastructure/docker-compose.yml up -d

compose-down:
	COMPOSE_PROJECT_NAME=zubr-locality \
	HOST_UID=$$(id -u $${USER}):$$(id -g $${USER}) \
	docker-compose -f infrastructure/docker-compose.yml \
 	down
