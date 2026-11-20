#!/usr/bin/env bash

printf "Syncing translations with translation.io...\n"
php artisan translation:sync

printf "Finished translation sync.\n"
