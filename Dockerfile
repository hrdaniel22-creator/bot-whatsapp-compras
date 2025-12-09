# Imagen basada en PHP 8
FROM php:8.2-cli

# Instalar extensiones necesarias (si las necesitas)
RUN docker-php-ext-install pdo pdo_mysql

# Copiar los archivos al contenedor
WORKDIR /app
COPY . /app

# Exponer puerto
EXPOSE 8080

# Iniciar servidor PHP
CMD php -S 0.0.0.0:8080


