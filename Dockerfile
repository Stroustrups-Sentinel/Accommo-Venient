FROM php:7.4-apache

# Install dependencies 
RUN docker-php-ext-install pdo pdo_mysql gd mbstring opcache zip xml \
    intl ctype iconv bcmath fileinfo imagick imagick_webp

# Copy files
COPY . /app/

# Copy SQL file
COPY accommo_venientdb.sql /docker-entrypoint-initdb.d/

# Setup MySQL volume 
VOLUME /var/lib/mysql

# Expose ports
EXPOSE 8080 

# MySQL
FROM mysql:5.7
ENV MYSQL_USER=root
ENV MYSQL_ROOT_PASSWORD=

# Set entrypoint to start PHP built-in web server
ENTRYPOINT ["php", "-S", "localhost:8080", "-t", "/app"]