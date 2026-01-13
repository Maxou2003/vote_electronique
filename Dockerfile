FROM php:8.2-apache

# Installer GMP
RUN apt-get update && apt-get install -y \
    libgmp-dev \
    && docker-php-ext-install gmp \
    && rm -rf /var/lib/apt/lists/*

# Copier le code dans le container
WORKDIR /var/www/html
COPY . /var/www/html

# Droits (simple pour dev)
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

EXPOSE 80
