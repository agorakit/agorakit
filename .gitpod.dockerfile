FROM gitpod/workspace-full

USER root

RUN apt-get update \
 && apt-get -y install apache2 multitail postgresql postgresql-contrib mysql-server mysql-client \
 && apt-get -y install php-cli php-bz2 php-bcmath php-gmp php-imap php-shmop php-soap php-xmlrpc php-xsl php-ldap \
 && apt-get -y install php-amqp php-apcu php-imagick php-memcached php-mongodb php-oauth php-redis\
 && apt-get clean && rm -rf /var/cache/apt/* /var/lib/apt/lists/* /tmp/*

RUN mkdir /var/run/mysqld

RUN chown -R gitpod:gitpod /var/run/apache2 /var/lock/apache2 /var/log/apache2 /etc/apache2 \
 && chown -R gitpod:gitpod /var/run/mysqld /usr/share/mysql /var/lib/mysql /var/log/mysql /etc/mysql

RUN echo 'ServerRoot ${GITPOD_REPO_ROOT}\n\
PidFile /var/run/apache2/apache.pid\n\
User gitpod\n\
Group gitpod\n\
IncludeOptional /etc/apache2/mods-enabled/*.load\n\
IncludeOptional /etc/apache2/mods-enabled/*.conf\n\
ServerName localhost\n\
Listen *:8000\n\
LogFormat "%h %l %u %t \"%r\" %>s %b" common\n\
CustomLog /var/log/apache2/access.log common\n\
ErrorLog /var/log/apache2/error.log\n\
<Directory />\n\
    AllowOverride All\n\
    Require all denied\n\
</Directory>\n\
DirectoryIndex index.php index.html\n\
DocumentRoot "${GITPOD_REPO_ROOT}/public"\n\
<Directory "${GITPOD_REPO_ROOT}/public">\n\
    Require all granted\n\
</Directory>\n\
IncludeOptional /etc/apache2/conf-enabled/*.conf' > /etc/apache2/apache2.conf

RUN a2enmod rewrite

RUN echo '[mysqld_safe]\n\
socket		= /var/run/mysqld/mysqld.sock\n\
nice		= 0\n\
[mysqld]\n\
user		= gitpod\n\
pid-file	= /var/run/mysqld/mysqld.pid\n\
socket		= /var/run/mysqld/mysqld.sock\n\
port		= 3306\n\
basedir		= /usr\n\
datadir		= /var/lib/mysql\n\
tmpdir		= /tmp\n\
lc-messages-dir	= /usr/share/mysql\n\
skip-external-locking\n\
bind-address		= 0.0.0.0\n\
key_buffer_size		= 16M\n\
max_allowed_packet	= 16M\n\
thread_stack		= 192K\n\
thread_cache_size   = 8\n\
myisam-recover-options  = BACKUP\n\
query_cache_limit	    = 1M\n\
query_cache_size        = 16M\n\
general_log_file        = /var/log/mysql/mysql.log\n\
general_log             = 1\n\
log_error               = /var/log/mysql/error.log\n\
expire_logs_days	= 10\n\
max_binlog_size     = 100M' > /etc/mysql/my.cnf

USER gitpod
ENV PATH="$PATH:/usr/lib/postgresql/10/bin"
ENV PGDATA="/home/gitpod/pg/data"
RUN mkdir -p ~/pg/data; mkdir -p ~/pg/scripts; mkdir -p ~/pg/logs; mkdir -p ~/pg/sockets; initdb -D pg/data/
RUN echo '#!/bin/bash\npg_ctl -D ~/pg/data/ -l ~/pg/logs/pgsql.log -o "-k ~/pg/sockets" start' > ~/pg/scripts/pg_start.sh
RUN echo '#!/bin/bash\npg_ctl -D ~/pg/data/ -l ~/pg/logs/pgsql.log -o "-k ~/pg/sockets" stop' > ~/pg/scripts/pg_stop.sh
RUN chmod +x ~/pg/scripts/*
ENV PATH="$PATH:$HOME/pg/scripts"

USER root