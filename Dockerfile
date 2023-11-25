# # MySQL
# # Setup MySQL volume 
# VOLUME /var/lib/mysql

# # Copy SQL file
# COPY accommo_venientdb.sql /docker-entrypoint-initdb.d/

# FROM mysql:5.7
# ENV MYSQL_USER=root
# ENV MYSQL_ROOT_PASSWORD=


FROM php:8.2-apache

# Install dependencies 
RUN docker-php-ext-install pdo_mysql pdo mysqli mbstring hash date bcmath filter

# Copy SQL file
COPY accommo_venientdb.sql /docker-entrypoint-initdb.d/

# Install mariadb
 RUN apt update \
    && apt-get -y install mariadb-server
    && systemctl start mariadb
    && systemctl enable mariadb

# Copy code files
COPY . /var/www/html/

# Expose ports
EXPOSE 80

CMD ["apache2ctl", "-D", "FOREGROUND"]

