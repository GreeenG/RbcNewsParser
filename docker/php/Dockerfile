FROM php:7.3-fpm

RUN apt-get update && apt-get install -y \
        libicu-dev \
        libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install \
        intl \
        opcache \
        pdo \
        pdo_pgsql \
        pgsql \
    && docker-php-ext-enable \
        intl \
        opcache

# Permission fix
RUN usermod -u 1000 www-data
