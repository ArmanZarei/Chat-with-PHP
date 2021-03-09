FROM composer:latest as vendor

WORKDIR /tmp/

COPY composer.json composer.json

RUN composer install \
    --ignore-platform-reqs \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist


FROM php:8.0.3-alpine

COPY . /var/www/html
COPY --from=vendor /tmp/vendor/ /var/www/html/vendor/

CMD ["php", "./var/www/html/server.php"]