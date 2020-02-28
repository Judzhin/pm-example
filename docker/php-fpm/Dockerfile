FROM php:7.3-fpm as scratch

ARG ENVIRONMENT
ENV ENVIRONMENT=${ENVIRONMENT}

COPY docker/php-fpm/php_${ENVIRONMENT}.ini /usr/local/etc/php/php.ini
COPY docker/php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

RUN apt-get update && apt-get install -y libpq-dev libicu-dev unzip wget \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo_pgsql intl \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN wget https://getcomposer.org/installer -O - -q | php -- --install-dir=/bin --filename=composer --quiet

ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /var/www/html

# DEV env (local)
FROM scratch as development

# PROD env (kubernetes)
FROM scratch as production