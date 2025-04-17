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
        libicu-dev \
        libxml2-dev && \
    apt-get clean && rm -rf /var/lib/apt/lists/* && \
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
    useradd -u 1000 -ms /bin/bash -g www www

USER www

COPY . /var/www

EXPOSE 9000

CMD ["php-fpm"]
