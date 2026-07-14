FROM php:8.2-apache

# Instalar dependencias del sistema operativo y librerías de desarrollo
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd zip

# Habilitar mod_rewrite para Apache (Crucial para Laravel)
RUN a2enmod rewrite

# Copiar el código del proyecto al contenedor
COPY . /var/www/html

# Configurar el directorio raíz de Apache a la carpeta public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ejecutar la instalación de dependencias
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader

# ... (todo lo anterior se queda igual)

# Dar permisos correctos a las carpetas de almacenamiento
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Puerto expuesto por Render
EXPOSE 80

# COMANDO DE INICIO: Aquí es donde se ejecutan las migraciones porque ya existen las variables de entorno reales.
CMD php artisan migrate --force && apache2-foreground