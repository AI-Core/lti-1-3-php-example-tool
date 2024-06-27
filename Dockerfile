FROM php:8-apache

# Define platform registration in environment variables.
ENV PLATFORM_ISSUER acme.com
ENV PLATFORM_CLIENT_ID some_random_uuid
ENV PLATFORM_LOGIN_URL https://acme.com/oauth2/authorize
ENV PLATFORM_TOKEN_URL https://acme.com/oauth2/token
ENV PLATFORM_JWKS_URL https://acme.com/jwks.json
ENV PLATFORM_DEPLOYMENT_ID some_random_uuid

RUN mkdir /srv/app

COPY ./src /srv/app

# Replace example registrations with registration from env vars. 
RUN rm /srv/app/db/configs/example.json && \
    echo '{' > /srv/app/db/configs/example.json && \
    echo '  "'"$PLATFORM_ISSUER"'": {' >> /srv/app/db/configs/example.json && \
    echo '    "client_id": "'"$PLATFORM_CLIENT_ID"'",' >> /srv/app/db/configs/example.json && \
    echo '    "auth_login_url": "'"$PLATFORM_LOGIN_URL"'",' >> /srv/app/db/configs/example.json && \
    echo '    "auth_token_url": "'"$PLATFORM_TOKEN_URL"'",' >> /srv/app/db/configs/example.json && \
    echo '    "key_set_url": "'"$PLATFORM_JWKS_URL"'",' >> /srv/app/db/configs/example.json && \
    echo '    "private_key_file": "/private.key",' >> /srv/app/db/configs/example.json && \
    echo '    "deployment": ["'"$PLATFORM_DEPLOYMENT_ID"'"]' >> /srv/app/db/configs/example.json && \
    echo '  }' >> /srv/app/db/configs/example.json && \
    echo '}' >> /srv/app/db/configs/example.json

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