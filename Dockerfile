FROM php:7-cli
RUN apt-get update && apt-get install -y pslib-dev && pecl install ps
COPY . /var/www/
COPY php.ini /usr/local/etc/php/
WORKDIR /var/www
CMD vendor/bin/phpunit --debug src 2>&1
