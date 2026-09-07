FROM php:8.4-fpm-alpine

ARG UID=1000
ARG GID=1000

RUN addgroup -g ${GID} --system laravel \
    && adduser -u ${UID} --system -G laravel -s /bin/sh -D laravel

RUN apk add --no-cache \
        freetype-dev \
        libjpeg-turbo-dev \
        libpng-dev \
        libwebp-dev \
        zlib-dev \
        libzip-dev \
        icu-dev \
        oniguruma-dev \
        libxml2-dev \
        linux-headers \
        autoconf \
        g++ \
        make \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        bcmath \
        zip \
        gd \
        intl \
        opcache \
        dom \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /tmp/pear

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY php-fpm-healthcheck /usr/local/bin/php-fpm-healthcheck
RUN chmod +x /usr/local/bin/php-fpm-healthcheck

RUN mkdir -p /var/www/html/storage/framework/{sessions,views,cache} \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R laravel:laravel /var/www/html

USER laravel

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
