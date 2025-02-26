# Usamos PHP con Apache
FROM php:8.2-apache

# Definir ARG para recibir el nombre del proyecto
# ARG CI_PROJECT_NAME

# Configurar directorio de trabajo
WORKDIR /var/www/html

# Instalamos extensiones necesarias
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libonig-dev \
    libicu-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli intl


# Establece el ServerName para evitar la advertencia
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Copiar la configuración de Apache
COPY docker/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Habilitar mod_rewrite para CodeIgniter
RUN a2enmod rewrite

# Install composer
#COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Descargar e instalar CodeIgniter con el nombre del proyecto
RUN [ ! -d "/var/www/html/public" ] && composer create-project codeigniter4/appstarter /var/www/html || echo "CodeIgniter ya instalado"

# Instalar dependencias de Composer
# RUN composer install --no-dev --optimize-autoloader

# Cambiar UID y GID del usuario www-data
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN chown -R www-data:www-data /var/www

# Establecer el usuario y grupo por defecto en el contenedor
USER www-data

