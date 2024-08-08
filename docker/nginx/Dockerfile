FROM php:8.x-fpm-alpine as build

# Installer les dépendances système
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    oniguruma-dev

# Installer les extensions PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo_mysql mbstring zip exif pcntl gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copier les fichiers du projet
WORKDIR /var/www/html
COPY ../../ .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Configurer les permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Construire l'image finale
FROM php:8.x-fpm-alpine

COPY --from=build /var/www/html /var/www/html

WORKDIR /var/www/html
EXPOSE 9000
CMD ["php-fpm"]
