FROM docker.io/dunglas/frankenphp

RUN install-php-extensions zip exif pcntl gd opcache imap

RUN apt-get -y update
RUN apt-get -y install git

COPY --from=composer /usr/bin/composer /usr/bin/composer

# UID and GID are arguments taken from environment, and are defined in compose.yml. 
# This avoid permission issues when the php process needs to write files.
ARG UID
ARG GID
 
ENV UID=${UID}
ENV GID=${GID}


#RUN addgroup -g ${GID} --system laravel
#RUN adduser -G laravel --system -D -s /bin/sh -u ${UID} laravel

# create a www user and give it the user and group id defined in our docker compose file (default is 1000)




WORKDIR /app
COPY . .

# On copie le fichier .env.example pour le renommer en .env
# Vous pouvez modifier le .env.example pour indiquer la configuration de votre site pour la production
RUN cp -n .env.prod .env

# Installation et configuration de votre site pour la production
# https://laravel.com/docs/10.x/deployment#optimizing-configuration-loading
RUN composer install --no-interaction --optimize-autoloader --no-dev
# Generate security key
RUN php artisan key:generate
# Optimizing Configuration loading
RUN php artisan config:cache
# Optimizing Route loading
RUN php artisan route:cache
# Optimizing View loading
RUN php artisan view:cache

RUN php artisan migrate



RUN chown -R application:application .