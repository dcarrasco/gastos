FROM php:7.3-fpm

RUN apt-get update \
    && apt-get install -y mysql-client \
    && docker-php-ext-install pdo_mysql
