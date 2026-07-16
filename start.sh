#!/bin/bash

# Ejecutar el scheduler de Laravel en background
php artisan schedule:work &

# Ejecutar Apache en foreground (mantiene el contenedor vivo)
apache2-foreground
