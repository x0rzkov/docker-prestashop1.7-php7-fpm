#!/bin/bash

set -e

getLatestFromRepo() {
    echo "git fetch && git pull;";
    git fetch && git pull;
}

dockerRefresh() {
    echo "docker-compose build";
    docker-compose build

    echo "docker-compose up -d";
    docker-compose up -d
}

. ${PWD}/.env;

dockerRefresh

