# Backend-Reserva-Ambientes
En este repositorio,desarrollaremos la parte logica del proyecto.
Incluyendo la creacion de CRUDs necesarios para el proyecto.
## Requisitos
PHP 7.2.20
Laravel 8
Composer 2.7.1
## Para ejecutar el proyecto depues de clonar el repositorio
Previamente copiar el ".env.example" y dejar la copia solo con el nombre ".env"
```bash
1. composer install
2. php artisan key:generate
3. php artisan serve
```
## Para ejecutar el proyecto
```bash
1. cd example-app
2. php artisan serve
```
## Para ejecutar las migraciones 
Debe esta configurado el .env para conectarse a la base de datos
```bash
1. php artisan migrate
2. php artisan migrate:fresh
```