FROM php:7.4-cli

RUN apt-get update \
    && apt-get install -y libpq-dev libgmp-dev libonig-dev libpng-dev libicu-dev zlib1g-dev libzip-dev git zip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
        mbstring \
        opcache \
        pdo pdo_mysql \
        sockets \
        bcmath \
        intl zip gd

RUN yes | pecl install xdebug

WORKDIR /app/api

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl --silent --show-error https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer && \
    composer clear-cache

COPY . /app/
