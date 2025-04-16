FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
	libpq-dev \
	unzip \
	&& docker-php-ext-install pdo pdo_pgsql

RUN a2enmod rewrite

COPY ./app /var/www/html

RUN chown -R www-data:www-data /var/www/html \ 
	&& chmod -R 755 /var/www/html

COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

EXPOSE 80