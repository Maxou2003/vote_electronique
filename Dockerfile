FROM php:8.2-apache

# Installer GMP
RUN apt-get update && apt-get install -y \
    libgmp-dev \
    && docker-php-ext-install gmp \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

# Autoriser .htaccess
RUN sed -i 's#<Directory /var/www/>#<Directory /var/www/>#' /etc/apache2/apache2.conf \
    && sed -i 's/AllowOverride None/AllowOverride All/i' /etc/apache2/apache2.conf

WORKDIR /var/www/html
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \;

EXPOSE 80
