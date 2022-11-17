FROM php:8.1.10-fpm-buster as scratch

ENV COMPOSER_VERSION 2.4.2
ENV UNAME=deploy
ENV UID=1000
ENV GID=1000
RUN groupadd -g $GID -o $UNAME
RUN useradd -m -u $UID -g $GID -o -s /bin/bash $UNAME
RUN usermod -a -G www-data $UNAME
RUN apt update \
    && apt install -y --no-install-recommends \
    unzip \
    wget \
    zip \
    git \
    curl \
    libpcre3-dev \
    libicu-dev \
    build-essential \
    chrpath \
    libpq-dev \
    libssl-dev \
    libxft-dev \
    libfreetype6 \
    libfreetype6-dev \
    libfontconfig1 \
    libfontconfig1-dev \
    libmagickwand-dev \
    libmemcached-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    $PHPIZE_DEPS \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo pgsql pdo_pgsql \
    && docker-php-ext-install opcache \
    && docker-php-ext-install intl \
    && docker-php-ext-install session \
    && pecl install redis && echo "extension=redis.so" > /usr/local/etc/php/conf.d/ext-redis.ini \
    && pecl install imagick && echo "extension=imagick.so" > /usr/local/etc/php/conf.d/ext-imagick.ini \
    && rm -rf /var/lib/apt/lists/* \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/

RUN curl https://phar.phpunit.de/phpunit.phar -L -o /usr/local/bin/phpunit && chmod a+x /usr/local/bin/phpunit

RUN curl --insecure https://getcomposer.org/download/${COMPOSER_VERSION}/composer.phar -o /usr/bin/composer && chmod a+x /usr/bin/composer


COPY ./.docker/php_composer/php.ini  /usr/local/etc/php/conf.d/docker-php-memlimit.ini
COPY ./.docker/php_composer/php-fpm.conf  /usr/local/etc/php-fpm.d/php-fpm.conf

RUN sed -i 's/www-data:x:33:33:www-data:\/var\/www:\/usr\/sbin\/nologin/www-data:x:33:33:www-data:\/var\/www:\/bin\/bash/g' /etc/passwd

WORKDIR /var/www

STOPSIGNAL SIGTERM

################################
### Backend image for deploy ###
################################
FROM scratch AS backend

COPY --chown=www-data:www-data . /var/www

WORKDIR /var/www

RUN set -eux \
    && mkdir -p /var/www/storage/logs \
    && touch /var/www/storage/logs/laravel.log \
    && chown -R www-data:www-data /var/www \
    && mkdir -p /var/www/.composer \
    && chown -R www-data:www-data /var/www/.composer \
    && mkdir -p /var/log/supervisor \
    && mkdir -p /var/www/.npm \
    && chown -R www-data:www-data /var/www/.npm \
    && mkdir -p /var/www/.config \
    && chown -R www-data:www-data /var/www/.config

USER www-data

WORKDIR /var/www

RUN php -d memory_limit=-1 /usr/bin/composer install --ignore-platform-reqs # -W --no-interaction

USER root

STOPSIGNAL SIGTERM
