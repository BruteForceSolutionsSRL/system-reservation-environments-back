#!/bin/bash

# Ruta al directorio de tu proyecto Laravel
PROJECT_DIR="/var/www/html"

# Comando para ejecutar el worker de cola
QUEUE_WORKER_COMMAND="php artisan queue:work"

# Cambiar al directorio del proyecto
cd $PROJECT_DIR

while true; do
  # Verificar si el proceso del worker de cola sigue ejecutándose
  if ps aux | grep queue:work | grep -v grep; then
    echo "El worker de cola ya está ejecutándose. Esperando..."
    sleep 10
  else
    # Iniciar el worker de cola
    nohup $QUEUE_WORKER_COMMAND &
    echo "Worker de cola iniciado."
  fi
done

