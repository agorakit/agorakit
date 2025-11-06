#!/usr/bin/env bash

/usr/bin/mariadb -uroot --password=password <<-EOSQL
    CREATE DATABASE IF NOT EXISTS agorakit_testing;
    GRANT ALL PRIVILEGES ON agorakit_testing.* TO 'agorakit'@'%';
EOSQL
