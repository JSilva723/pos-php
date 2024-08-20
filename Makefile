#!/bin/bash

UID = $(shell id -u)
DOCKER_BE = web-pos
DOCKER_DB = mysql-pos

start:
	U_ID=${UID} docker compose up -d

stop:
	U_ID=${UID} docker compose stop

restart:
	$(MAKE) stop && $(MAKE) start

build:
	U_ID=${UID} docker compose build

ssh:
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_BE} bash

db-ssh:
	U_ID=${UID} docker exec -it --user ${UID} ${DOCKER_DB} bash