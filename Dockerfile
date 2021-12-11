FROM phpswoole/swoole:4.8-php8.0

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app

RUN apt-get update && \
        apt-get upgrade -y && \
        apt-get install -y git zip unzip libzip4 libpq-dev

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo pdo_pgsql

RUN pecl install redis && docker-php-ext-enable redis

COPY . ./

RUN composer install

ENTRYPOINT ["bin/console", "swoole:server:run"]
