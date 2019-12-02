#!/bin/bash

set -e

getLatestFromRepo() {
    echo "git fetch && git pull;";
    git fetch && git pull;
}

dockerRefresh() {
    echo "docker-compose build";
    docker-compose -f docker-compose.osx.yml up -d --build

    echo "docker-compose up -d";
    docker-compose up -d
}

dockerSync() {
	docker-sync stop && docker-sync clean && docker-sync start
}

. ${PWD}/.env;
dockerRefresh
dockerSync
