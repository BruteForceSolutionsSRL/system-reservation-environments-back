# Guía de preparación del software necesario para la ejecución del proyecto en Linux (Distribuciones basadas en Debian o con gestor de paquetes APT)

## Paquetes a instalar:

### Apache

```bash
sudo apt install apache2
```

### MariaDB

```bash
sudo apt install mariadb-server
```

### PHP 7.4

##### En caso de que PHP 7.4 no este disponible en los repositorios oficiales del gestor de paquetes de Linux

Ejecute los siguientes comandos:

```bash
sudo apt install -y apt-transport-https lsb-release ca-certificates wget 
wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | sudo tee /etc/apt/sources.list.d/php.list
sudo apt update
```

Para mas información: https://tecadmin.net/how-to-install-php-on-debian-12/

##### Instalación de PHP 7.4

```bash
sudo apt install php7.4 libapache2-mod-php7.4 php7.4-mysql php7.4-cli php7.4-json php7.4-curl php7.4-gd php7.4-xml php7.4-zip php7.4-mbstring php7.4-gmp
```

### Composer:

Ejecute los siguientes comandos:

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

Se descargaran los archivos composer-setup.php y composer.phar, debe mover este último archivo al directorio /usr/local/bin/

```bash
sudo mv composer.phar /usr/local/bin/composer
```

Para mas información: https://getcomposer.org/download/

## Inicio rapido

Clone o descargue el repositorio

```bash
git clone https://github.com/BruteForceSolutionsSRL/reservation-system-back.git
```

Dirígase al directorio raíz del proyecto

```bash
cd reservation-system-back
```

Ejecute el siguiente comando:

```bash
composer install
```

Copie y renombre el archivo .env.example

```bash
cp .env.example .env
```

Actualice el archivo .env con sus credenciales de acceso a MySQL o MariaDB (debe tener la base de datos creada)

Ejecute las migraciones:

```bash
php artisan migrate
```

Inicie el servidor:

```bash
php artisan serve
```
