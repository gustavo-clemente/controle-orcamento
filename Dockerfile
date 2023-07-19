FROM php:8.1-fpm


RUN apt-get update && apt-get install -y --no-install-recommends \
    wget \
    unzip

RUN wget https://getcomposer.org/installer -O composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm composer-setup.php

RUN docker-php-ext-install pdo_mysql

WORKDIR /usr/src/controle-orcamento
COPY . .

ENV JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
ENV JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem

RUN composer install --optimize-autoloader --no-scripts
RUN openssl genrsa -out config/jwt/private.pem 4096
RUN openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem

EXPOSE 8000

ENTRYPOINT [ "php" , "-S", "0.0.0.0:8000", "-t", "public" ]