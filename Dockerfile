# syntax=docker/dockerfile:1

FROM php:8-fpm

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

WORKDIR /var/www/html
