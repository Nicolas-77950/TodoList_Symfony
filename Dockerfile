# Passage à PHP 8.4
FROM php:8.4-fpm-alpine

# Installation des dépendances système
RUN apk add --no-cache \
    bash \
    icu-dev \
    libpq-dev \
    git \
    unzip \
    acl

# Installation des extensions PHP
RUN docker-php-ext-install \
    intl \
    pdo_pgsql \
    pgsql

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# On copie les fichiers mais on ne lance pas le "RUN composer install" ici
# car si ça échoue au build, on ne voit pas l'erreur. 
# On le fera au démarrage du container.
COPY . .

CMD ["php-fpm"]

EXPOSE 9000