FROM php:7.2-fpm

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        vim \
        iproute2 \
        git \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libfreetype6-dev \
        libjpeg-dev \
        libxpm-dev \
        libwebp-dev \
        libmcrypt-dev \
        libicu-dev \
        libzip-dev \
        zip \
        unzip \
        libpq-dev \
    && docker-php-ext-install \
       bcmath \
       intl \
       pdo_pgsql \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install zip \
    && docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/ \
        --with-xpm-dir=/usr/include \
        --with-webp-dir=/usr/include/ \
    && pecl install xdebug-2.6.1 \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=BONUZO" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ARG UID
ARG GID
RUN usermod -u ${UID} www-data \
    && groupmod -g ${GID} www-data \
    && chown -R www-data:www-data /var/www
USER www-data
