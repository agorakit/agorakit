FROM docker.io/dunglas/frankenphp

RUN install-php-extensions pdo_mysql mbstring zip exif pcntl gd opcache imap

COPY --from=composer /usr/bin/composer /usr/bin/composer