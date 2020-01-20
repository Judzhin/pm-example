FROM php:7.3-fpm

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN apt-get update && apt-get install -y libpq-dev libicu-dev unzip wget \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql intl \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /var/www
