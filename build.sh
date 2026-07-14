#!/usr/bin/env bash
# Salir inmediatamente si un comando falla
set -o errexit

# Instalar dependencias de Composer
composer install --no-dev --optimize-autoloader

# Si usas Vite/NPM para el frontend (descomenta si es necesario)
# npm install && npm run build

# Optimizar la configuración de Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones de base de datos (opcional, pero recomendado)
# php artisan migrate --force