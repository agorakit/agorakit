#!/usr/bin/env bash

printf "Finding new strings in code...\n"
php artisan localize

printf "Syncing translations with translation.io...\n"
php artisan translation:sync

printf "Finished translation sync.\n"
