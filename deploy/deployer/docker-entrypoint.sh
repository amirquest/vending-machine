#!/bin/bash

if [ ! -d "vendor" ]; then
    composer install --optimize-autoloader --prefer-dist --no-ansi --no-interaction --no-progress --no-scripts
fi

gosu www php artisan optimize:clear
gosu www php artisan migrate --seed --force
gosu www php artisan optimize
gosu www php artisan filament:optimize-clear
gosu www php artisan filament:optimize
gosu www php artisan about

exec php-fpm
