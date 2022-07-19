FROM php:7.3.33-fpm-alpine

# Install required libraries
RUN apk add --update --no-cache --virtual .ext-deps \
    mysql-client \
    postgresql-dev \
    zip \
    libpng-dev \
    imagemagick-dev \
    libtool

# Install PHP extensions
RUN apk add --no-cache \
        $PHPIZE_DEPS \
    && pecl install mailparse \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && docker-php-ext-enable mailparse \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install gd \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install pgsql \
    && docker-php-ext-install opcache
