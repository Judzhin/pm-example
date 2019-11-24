up:
    docker-compose up -d

build:
    docker-compose up -d --build

composer-install:
    docker_compose run --rm pm-php-cli composer install

composer-install-without-dev:
    docker_compose run --rm pm-php-cli composer install --no-dev