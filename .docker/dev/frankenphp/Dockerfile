FROM dunglas/frankenphp:php8.2 AS frankenphp

# Configure PHP
RUN cp $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

RUN install-php-extensions pdo_mysql zip exif pcntl intl gd opcache imap bcmath

# Copy Composer binary from latest Docker image.
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install Xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=develop,coverage,debug,profile" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Setup file permissions
ARG GID

ARG UID

RUN groupadd -g ${GID} www; \
	useradd -g ${GID} -u ${UID} www

# Allow binding to privileged ports 80 and 443
RUN setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp

# Give write access to /data/caddy and /config/caddy
RUN chown -R ${UID}:${GID} /data/caddy && chown -R ${UID}:${GID} /config/caddy

USER ${UID}
