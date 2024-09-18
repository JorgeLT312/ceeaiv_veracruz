#!/bin/sh


# Inicia PHP-FPM
php-fpm


# Laravel Comandos
composer install

php artisan key:generate

php artisan storage:link
