#!/bin/bash

while getopts ":dafp:r:b:e:" flag;
do
    case "${flag}" in
        a) hasAuth=true;;
        f) hasFileUpload=true;;
        p) port=${OPTARG};;
        d) docker=true;;
        r) redisPort=${OPTARG};;
        b) dbPort=${OPTARG};;
        e) environment=${OPTARG};;
    esac
done

if [ -n "$docker" ]; then
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v "$(pwd):/var/www/html" \
        -w /var/www/html \
        laravelsail/php82-composer:latest \
        composer install --ignore-platform-reqs
else
    composer install
fi

if [ ! -n "$environment" ]; then
   environment=local
fi

if [ ! -f .env.$environment ]; then
   cp .env.development .env.$environment;
   sed -i -e  "s/APP_ENV=development/APP_ENV=$environment/" .env.$environment
fi

currentEnv=$(echo $(grep -v '^#' docker-compose.yml | grep -e "APP_ENV" | sed -e 's/.*://'))
sed -i -e  "s/APP_ENV: $currentEnv/APP_ENV: '$environment'/" docker-compose.yml


if [ -n "$port" ]; then
    sed -i -e  "s/APP_PORT=3000/APP_PORT=$port/" .env.$environment
fi

if [ -n "$redisPort" ]; then
    sed -i -e  "s/FORWARD_REDIS_PORT=6379/FORWARD_REDIS_PORT=$redisPort/" .env.$environment
fi

if [ -n "$dbPort" ]; then
    sed -i -e  "s/FORWARD_DB_PORT=5432/FORWARD_DB_PORT=$dbPort/" .env.$environment
fi

rm docker-compose.yml-e
rm .env.$environment-e

cp .env.$environment .env

currentKey=$(echo $(grep -v '^#' .env.$environment | grep -e "APP_KEY"))

SAIL=./vendor/bin/sail
$SAIL up -d

if [  $currentKey == "APP_KEY="  ]; then
    $SAIL php artisan key:generate --ansi
fi

$SAIL php artisan migrate

if [ -n "$hasAuth" ]; then
    $SAIL php artisan passport:keys
fi

if [ -n "$hasFileUpload" ]; then
    $SAIL php artisan storage:link || true
fi

$SAIL composer additional-commands

