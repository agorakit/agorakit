FROM docker.io/dunglas/frankenphp

RUN install-php-extensions pdo_mysql zip exif pcntl gd opcache imap

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

RUN \
	# create group and user with correct ID
	groupadd -g ${GID} www; \
	useradd -g ${GID} -u ${UID} www; \
	# Add capacity to bind to the privilegied ports 80 and 443
	setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp; \
	# Give write access to /data/caddy and /config/caddy
	chown -R www:www /data/caddy && chown -R www:www /config/caddy

USER www
