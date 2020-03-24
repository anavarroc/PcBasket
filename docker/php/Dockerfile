FROM php:7.4.4-fpm-alpine3.11
LABEL maintainer = VoodooGoblin


RUN docker-php-ext-install bcmath
RUN apk add --no-cache libzip-dev \
    && docker-php-ext-configure zip \
#    --with-libzip=/usr/include  \
    && docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html