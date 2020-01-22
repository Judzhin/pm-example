#!/usr/bin/env bash

# Path to ASCII Art logo
FILE=${PWD}/vendor/bin/msbios.sh;

# Show ASCII Art if is it perhaps
if [ -f $FILE ]; then
    bash $FILE
fi

echo 'Run doctrine migrations';
docker-compose run --rm pm-php-cli php bin/console doctrine:migrations:migrate --no-interaction

echo 'Run load doctrine fixtures';
docker-compose run --rm pm-php-cli php bin/console doctrine:fixtures:load --no-interaction