FROM php:7.4.0-fpm
COPY php.ini /usr/local/etc/php/

RUN docker-php-ext-install pdo_mysql
# RUN apt-get update && apt-get -y install libpq-dev \
#     && docker-php-ext-install pdo pdo_pgsql 



