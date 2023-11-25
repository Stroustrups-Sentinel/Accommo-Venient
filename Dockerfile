FROM php:8.2-cli

# Install dependencies 
# RUN docker-php-ext-install curl hash date filter ftp imap pdo pdo_mysql openssl mysqli exif gd mbstring opcache zlib  zip xml \
#     intl ctype iconv bcmath fileinfo

# Copy files
COPY . /app/

# # Copy SQL file
# COPY accommo_venientdb.sql /docker-entrypoint-initdb.d/

# # Setup MySQL volume 
# VOLUME /var/lib/mysql

# # Expose ports
# EXPOSE 8080 

# # MySQL
# FROM mysql:5.7
# ENV MYSQL_USER=root
# ENV MYSQL_ROOT_PASSWORD=

# Set entrypoint to start PHP built-in web server
ENTRYPOINT ["php", "-S", "localhost:8080", "-t", "/app"]
