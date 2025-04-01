#!/usr/bin/env bash

printf "Suspending app...\n"
php artisan down --refresh=30 --render="errors::503"

printf "Updating code...\n"
git checkout main
git pull

printf "Refreshing dependencies...\n"
composer install --ignore-platform-reqs

printf "Updating database...\n"
php artisan migrate --force

printf "Clearing cache...\n"
php artisan view:cache
php artisan config:cache
php artisan event:cache
php artisan route:clear

printf "Restoring app...\n"
php artisan up

printf "Finished update.\n"
