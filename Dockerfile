# Setup MySQL volume 
VOLUME /var/lib/mysql

# Copy SQL file
COPY accommo_venientdb.sql /docker-entrypoint-initdb.d/

# MySQL
FROM mysql:5.7
ENV MYSQL_USER=root
ENV MYSQL_ROOT_PASSWORD=


FROM php:8.2-apache

# Install dependencies 
RUN docker-php-ext-install pdo_mysql pdo mysqli mbstring hash date bcmath filter

# RUN docker-php-ext-install curl hash date filter ftp imap pdo pdo_mysql openssl mysqli exif gd mbstring opcache zlib  zip xml \
#     intl ctype iconv bcmath fileinfo

# Copy code files
COPY . /var/www/html/

# Expose ports
EXPOSE 80

CMD ["apache2ctl", "-D", "FOREGROUND"]

