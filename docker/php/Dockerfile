FROM php:8.0.2-fpm-alpine as stateful

ARG ENV=dev
ARG XDEBUG_ENABLED=0

RUN set xe && \
    cd /tmp/ && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('sha384', 'composer-setup.php') === '906a84df04cea2aa72f40b5f787e49f22d4c2f19492ac310e8cba5b96ac8b64115ac402c8cd292b8a03482574915d1a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php --install-dir=/usr/local/bin/ --filename=composer && \
    php -r "unlink('composer-setup.php');"

RUN set xe && \
    cd /tmp/ && \
    apk add --no-cache --update mysql-dev && \
    docker-php-ext-install -j5 pdo pdo_mysql

FROM stateful AS stateless

ARG ENV=dev
ENV COMPOSER_HOME=/app/.composer.cache

COPY ./ /app

COPY ./docker/php/php.ini-production "$PHP_INI_DIR/php.ini-production"

RUN if [ "${ENV}" = "prod" ] ;then set -xe && \
    mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
;fi

RUN if [ "${ENV}" = "dev" ] ;then set -xe && \
    mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" \
;fi

WORKDIR /app

RUN set -xe && \
    echo APP_ENV="$ENV" > .env.local && \
    echo DATABASE_URL="" >> .env.local && \
    if [ "${ENV}" = "prod" ] ;then \
    composer install --optimize-autoloader --no-dev \
    ;else \
    composer install --optimize-autoloader \
    ;fi && \
    composer dump-autoload --classmap-authoritative --optimize && \
    composer dump-env "$ENV" && \
    php bin/console cache:warmup && \
    rm -rf $COMPOSER_HOME