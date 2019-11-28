#!/bin/bash

# remove volumes
docker volume rm $(docker volume ls)
