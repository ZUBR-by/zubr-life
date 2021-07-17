FROM php:8.0-fpm

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

RUN yes | pecl install xdebug-3.0.4

WORKDIR /app/api

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN curl --silent --show-error https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer && \
    composer clear-cache

COPY infrastructure/php/config/www.conf /usr/local/etc/php-fpm.d/www.conf
CMD ["php-fpm", "--allow-to-run-as-root"]

ADD api/composer.json api/composer.lock ./
RUN composer install --no-scripts --no-autoloader --no-suggest && \
    composer clear-cache
COPY . /app/
RUN composer dump-autoload --optimize
