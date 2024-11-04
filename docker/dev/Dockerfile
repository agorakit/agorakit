FROM docker.io/dunglas/frankenphp

RUN install-php-extensions pdo_mysql zip exif pcntl gd opcache imap

COPY --from=composer /usr/bin/composer /usr/bin/composer


RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

#RUN setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp; \
#RUN chown -R www:www /data/caddy && chown -R www:www /config/caddy

RUN \
	# create group and user with correct ID
	groupadd -g 1000 www \
	useradd -D www \
	# Add capacity to bind to the privilegied ports 80 and 443
	setcap CAP_NET_BIND_SERVICE=+eip /usr/local/bin/frankenphp; \
	# Give write access to /data/caddy and /config/caddy
	chown -R www:www /data/caddy && chown -R www:www /config/caddy

USER www

# Print the UID and GID
#CMD sh -c "echo 'Inside Container:' && echo 'User: $(whoami) UID: $(id -u) GID: $(id -g)'"