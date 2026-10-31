# Imagen base oficial de PHP con servidor web Apache
FROM php:8.2-apache

# Instalar las extensiones requeridas para conectar con MySQL (mysqli y pdo_mysql)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar el módulo rewrite de Apache
RUN a2enmod rewrite

# Copiar el código fuente de la aplicación al directorio raíz web de Apache
COPY src/ /var/www/html/

# Ajustar permisos para el usuario www-data de Apache
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto estándar HTTP
EXPOSE 80
