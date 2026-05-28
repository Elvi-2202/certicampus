FROM php:8.5-fpm

# Installation des dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libpng-dev \
    libxml2-dev \
    zip \
    curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP
RUN docker-php-ext-install pdo pdo_mysql intl zip

# Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# On expose le port 9000 (le port par défaut de PHP-FPM)
EXPOSE 9000

CMD ["php-fpm"]