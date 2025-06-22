FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip sqlite3 libsqlite3-dev

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

EXPOSE 80

CMD ["apache2-foreground"]
