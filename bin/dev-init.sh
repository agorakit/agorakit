#!/usr/bin/env bash

# New ENV?
ENV_FILE=./.env.dev
if [ ! -f "$ENV_FILE" ]; then
    echo "No .env.dev file found. Copying .env.docker."
    ENV_NEW=true
    cp .env.docker .env.dev
fi

# Build containers.
export UID
export GID="$(id -g)"
docker compose -f compose.dev.yml up --build -d

# Setup app.
docker exec -it agorakit-dev sh -c "composer install"
docker exec -it agorakit-dev sh -c "php artisan migrate --env=dev"
if [ "$ENV_NEW" = true ]; then
    # Rebuild with new key in ENV (`restart` does not reload ENV).
    echo "Rebuilding container with Laravel key."
    docker exec -it agorakit-dev sh -c "php artisan key:generate --env=dev"
    docker compose -f compose.dev.yml up -d
fi

# Enter workspace.
docker exec -it agorakit-dev bash
