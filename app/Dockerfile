FROM php:7.4-apache
RUN apt-get update -y
RUN docker-php-ext-install mysqli
RUN apt-get install jq -y
COPY html /var/www/html

RUN chown -R root:www-data /var/www/html
RUN chmod -R 777 /var/www/html
RUN a2enmod headers
RUN a2enmod rewrite
RUN /etc/init.d/apache2 restart
