#!/usr/bin/env bash

# Build containers.
export UID
export GID="$(id -g)"
docker compose -f compose.dev.yml up --build -d
#docker logs -f agorakit-dev

# Setup app.
docker exec -it agorakit-dev sh -c "composer install"
docker exec -it agorakit-dev sh -c "php artisan key:generate --env=dev"
docker exec -it agorakit-dev sh -c "php artisan migrate --env=dev"

# Enter workspace (frankenphp).
docker exec -it agorakit-dev bash
