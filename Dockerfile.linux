ARG PHP_VERSION=8.0
FROM faulo/farah:${PHP_VERSION}

RUN apt update && apt upgrade -y

COPY --chown=www-data:www-data . .
RUN chmod 777 .

USER www-data
RUN composer update --no-interaction --no-dev --optimize-autoloader --classmap-authoritative