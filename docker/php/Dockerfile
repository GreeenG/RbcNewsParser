FROM php:7.3-fpm

RUN apt-get update && apt-get install -y gnupg2

RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list

RUN apt-get update && apt-get install -y \
        yarn \
        libicu-dev \
        libpq-dev \
        git \
        libzip-dev \
        zlib1g-dev \
        unzip \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install \
        intl \
        opcache \
        pdo \
        pdo_pgsql \
        pgsql \
        zip \
    && docker-php-ext-enable \
        intl \
        opcache
RUN curl -L -o - https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
    && alias composer="php /usr/bin/composer"

# Permission fix
RUN usermod -u 1000 www-data
