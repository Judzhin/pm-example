#!/usr/bin/env bash

# Path to ASCII Art logo
FILE=${PWD}/vendor/bin/msbios.sh;

# Show ASCII Art if is it perhaps
if [ -f $FILE ]; then
    bash $FILE
fi

echo 'Clear dependency file'
docker run --rm -v ${PWD}:/var/www --workdir=/var/www alpine rm -f project.pid

echo 'Start rebuild containers';
docker-compose up -d --build

echo 'Install Composer Dependencies';
docker-compose run --rm pm-php-fpm composer install

echo 'Require Encore Dependency';
docker-compose run --rm pm-php-fpm composer require encore

echo 'Run update postgresql';
docker-compose run --rm pm-php-fpm php bin/console doctrine:schema:update --force

echo 'Run doctrine migrations';
docker-compose run --rm pm-php-fpm php bin/console doctrine:migrations:migrate --no-interaction

echo 'Run load doctrine fixtures';
docker-compose run --rm pm-php-fpm php bin/console doctrine:fixtures:load --no-interaction

echo 'Install Statics Dependencies';
docker-compose run --rm pm-node-cli yarn add -s bootstrap jquery popper.js
docker-compose run --rm pm-node-cli yarn add -s --dev sass-loader node-sass
docker-compose run --rm pm-node-cli yarn add -s @coreui/coreui font-awesome perfect-scrollbar simple-line-icons

echo 'All containers are done'
docker run --rm -v ${PWD}:/var/www --workdir=/var/www alpine touch project.pid