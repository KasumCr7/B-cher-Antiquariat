FROM php:8.2-apache

# PDO MySQL Extension aktivieren
RUN docker-php-ext-install pdo pdo_mysql

# Apache mod_rewrite aktivieren
RUN a2enmod rewrite

# Apache: Verzeichnis auf /var/www/html setzen
ENV APACHE_DOCUMENT_ROOT /var/www/html

# Projektdateien kopieren
COPY . /var/www/html/

# Berechtigungen setzen
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
