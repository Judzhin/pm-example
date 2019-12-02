#!/usr/bin/env bash
echo 'Start rebuild application';
docker-compose up -d --build

echo 'Run update postgresql';
docker-compose run --rm pm-php-cli php bin/console doctrine:schema:update --force

echo 'Run load fixtures';
docker-compose run --rm pm-php-cli php bin/console doctrine:fixtures:load
