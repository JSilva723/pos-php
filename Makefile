#!/bin/bash

UID = $(shell id -u)
DOCKER_BE = main-app
DOCKER_DB = main-app-mysql

start:
	U_ID=${UID} docker compose up -d

stop:
	U_ID=${UID} docker compose stop

restart:
	$(MAKE) stop && $(MAKE) start

build:
	U_ID=${UID} docker compose build

prepare:
	$(MAKE) composer-install

run:
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} symfony serve -d

logs:
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} symfony server:log

composer-install:
	U_ID=${UID} docker exec --user ${UID} ${DOCKER_BE} composer install --no-interaction

ssh:
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bash

db-ssh:
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_DB} bash