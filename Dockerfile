FROM php:8-apache

RUN mkdir /srv/app

COPY ./src /srv/app

COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    && apt clean

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN chown -R www-data:www-data /srv/app \
    && a2enmod rewrite

WORKDIR /srv/app

RUN composer install && composer update

ENTRYPOINT [ "apache2-foreground" ]