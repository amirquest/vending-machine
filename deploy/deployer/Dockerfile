FROM php:8.3-fpm

WORKDIR /var/www

RUN apt-get update && apt-get upgrade -y && \
    apt-get install -y --no-install-recommends \
        curl \
        zip \
        unzip \
        git \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libonig-dev \
        libssl-dev \
        librabbitmq-dev \
        gosu \
        libicu-dev \
        libxml2-dev && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo_mysql pcntl sockets exif opcache zip gd intl soap && \
    docker-php-ext-enable intl soap

RUN pecl install redis amqp && \
    docker-php-ext-enable redis amqp && \
    echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini && \
    echo "extension=amqp.so" > /usr/local/etc/php/conf.d/amqp.ini && \
    rm -f /usr/local/etc/php/conf.d/docker-php-ext-redis.ini /usr/local/etc/php/conf.d/docker-php-ext-amqp.ini

COPY --from=composer:2.7.7 /usr/bin/composer /usr/bin/composer

RUN groupadd -g 1000 www && \
    useradd -u 1000 -ms /bin/bash -g www www && \
    mkdir -p /var/www/storage && \
    chown -R www:www /var/www && \
    chmod -R 777 /var/www/storage

RUN sed -i 's/listen = 127.0.0.1:9000/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf && \
    sed -i 's/listen = 9000/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/zz-docker.conf

COPY --chown=www:www . /var/www
COPY --chown=www:www ./deploy/deployer/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 9000

HEALTHCHECK --interval=30s --timeout=10s --retries=3 CMD curl -f http://localhost:9000 || exit 1

ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

#CMD ["php artisan serve --host=0.0.0.0 --port=9000"]
