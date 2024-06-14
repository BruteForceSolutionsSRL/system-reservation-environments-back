# Guía de instalación y configuración del proyecto en Windows
## Paso 1: Instalar PHP versión 7.2.20
Si tienes otra versión de XAMPP instalada, se recomienda desinstalarla. Usaremos el instalador de XAMPP, que puedes obtener desde estos enlaces:

```bash
https://drive.google.com/driveu2folders1FmtMWEwhSkI96SJRBucKg8DxiZU0cTn2
```
Alternativa
```bash
https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/7.2.20/
```

Nota: Es importante instalar XAMPP en el directorio raíz de la computadora (C:\Xampp).

## Paso 2: Configurar el PATH de PHP en las variables de entorno
Puedes hacer esto manualmente copiando la ruta donde se encuentra php.exe (por ejemplo, C:\xampp\php) y pegándola en el PATH de las variables de entorno, tanto para el equipo como para el usuario.

Otra opción es instalar Composer para que se realice automáticamente. Descarga Composer desde:

```bash
https://getcomposer.org/download/  # Composer-Setup.exe
```
Instálalo y elige la opción para instalarlo para todos los usuarios. Luego, presiona "Next" en todas las opciones.

Para probar que tienes instalado Composer, abre una terminal y ejecuta el comando composer.

## Paso 3: Descarga y configuración del repositorio del backend
Descarga el repositorio del backend desde:

```bash
https://github.com/BruteForceSolutionsSRL/reservation-system-back.git
```

Repositorio en GitHub
Después de descargar el proyecto, instala las dependencias con el comando:

```bash
composer install
```

Luego, configura las variables de entorno para la conexión con la base de datos.

Copia el archivo .env.example y pégalo en la carpeta raíz del proyecto del backend. Cambia el nombre del archivo a .env.

En el archivo .env, configura la conexión a la base de datos de la siguiente manera:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=artisan  # Pon el nombre de tu base de datos
DB_USERNAME=root
DB_PASSWORD=         # Ingresa tu contraseña
```

## Paso 4: Migrar la base de datos
Ejecuta el siguiente comando para llenar todas las tablas en tu base de datos:

```bash
php artisan migrate
```

## Paso 5: Ejecutar el proyecto
Ubícate en la carpeta raíz del proyecto reservation-system-back y ejecuta el proyecto con:

```bash
php artisan serve
```

Opcionalmente, si tienes errores, puedes ejecutar:

```bash
php artisan key:generate
```

## Actualización de la base de datos
Si hay cambios en la base de datos, ejecuta el siguiente comando para refrescarla:

```bash
php artisan migrate:fresh
```
Nota: Ten en cuenta que este comando eliminará todos los datos en la base de datos local.

## Paso 6: Rellenar los datos de la base de datos
Para rellenar los datos de la base de datos, ejecuta el archivo ".sql" correspondiente.