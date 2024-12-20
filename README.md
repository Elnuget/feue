Instalación:
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install

Para iniciar:
npm run dev
php artisan serve

para migraciones:
php artisan migrate:fresh --seed


para roles
rutas protegidas con spatie

para vistas
 tienes que usar <x-app-layout> no @extends('layouts.app')

@section('content')

Revertir cambios
git fetch origin
git reset --hard origin/main

para imagenes:
php artisan storage:link
EN PRODUCCION
agregar trabajo de cron

para ruta nueva creada o repo clonado:
php artisan route:clear
php artisan route:cache


para excel:
phpoffice/phpspreadsheet

Intrucciones para produccion:
composer install --optimize-autoloader --no-dev
npm install && npm run build
contenido de storage/app/public


