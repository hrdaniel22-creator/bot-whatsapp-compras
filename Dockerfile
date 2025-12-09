Imagen base con PHP 8

FROM php:8.2-cli

Crear carpeta del proyecto

WORKDIR /app

Copiar todos los archivos del repo

COPY . .

Exponer el puerto 10000

EXPOSE 10000

Comando para ejecutar el servidor PHP

CMD [“php”, “-S”, “0.0.0.0:10000”]
