FROM php:8.0-apache

# Instalar dependências e as extensões mysqli e pdo_mysql
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libxml2-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql

# Habilitar a extensão mysqli e pdo_mysql
RUN docker-php-ext-enable mysqli pdo_mysql


# Habilita mod_rewrite
RUN a2enmod rewrite

# Habilita AllowOverride All no Apache
RUN sed -i 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Atualizar o Apache
RUN apt-get update && apt-get upgrade -y
