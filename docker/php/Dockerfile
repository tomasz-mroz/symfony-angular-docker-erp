FROM php:8.2-fpm

# Instalacja zależności systemowych
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    librabbitmq-dev \
    libssl-dev \
    && rm -rf /var/lib/apt/lists/*

# Instalacja i włączenie rozszerzeń PHP
RUN docker-php-ext-install pdo pdo_pgsql \
    && pecl install amqp redis \
    && docker-php-ext-enable amqp redis pdo_pgsql

# Instalacja Composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/backend