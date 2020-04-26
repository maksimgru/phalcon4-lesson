#!/usr/bin/env bash

#cp application/.env.example application/.env
cp docker-compose.yml.dist docker-compose.yml
cp env/build.sh.dist env/build.sh
cp env/docker/nginx/nginx.conf.dist env/docker/nginx/nginx.conf
cp env/docker/php-fpm/php.ini.dist env/docker/php-fpm/php.ini
