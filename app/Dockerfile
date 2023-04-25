FROM php:7.4-apache
RUN apt-get update -y
RUN docker-php-ext-install mysqli
RUN apt-get install jq -y
COPY html /var/www/html
COPY flag.txt /flag.txt

RUN chown 400 /flag.txt
RUN rm /var/log/apache2/access.log /var/log/apache2/error.log
RUN touch /var/log/apache2/access.log /var/log/apache2/error.log
RUN chown -R www-data:www-data /var/log/apache2
RUN chmod -R 777 /var/log/apache2
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 777 /var/www/html
RUN a2enmod headers
RUN a2enmod rewrite
RUN /etc/init.d/apache2 restart
