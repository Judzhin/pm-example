up:
    docker-compose up -d

build:
    docker-compose up -d --build

composer-install:
    docker-compose run --rm pm-php-cli composer install

composer-install-without-dev:
    docker-compose run --rm pm-php-cli composer install --no-dev

composer-install-encore:
    docker-compose run --rm pm-php-cli composer require encore

composer-install-assets-dependencies:
    docker-compose run --rm pm-node yarn add -s bootstrap jquery popper.js

composer-install-dev-assets-dependencies:
    docker-compose run --rm pm-node yarn add -s --dev sass-loader node-sass

