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

para ruta nueva creada o repo clonado:
php artisan route:clear
php artisan route:cache
