FROM php:7.4-fpm-alpine

RUN apk update && apk add composer postgresql-dev

RUN docker-php-ext-install pdo_pgsql
