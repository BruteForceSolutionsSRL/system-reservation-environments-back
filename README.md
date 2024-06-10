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
## Para ejecutar el proyecto asincronico
Se debe actualizar del .env con lo siguiente:
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=bruteforcesolutionsbfs@gmail.com
MAIL_FROM_NAME="Sistema SURA"
```
Tambien:
```bash
BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=120
```
Ejecutar en una terminal de comandos aparte ubicado en el proyecto
```bash
1. php artisan queue:Work
```
