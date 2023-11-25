FROM php:8.2-apache

# Install dependencies 
# RUN docker-php-ext-install curl hash date filter ftp imap pdo pdo_mysql openssl mysqli exif gd mbstring opcache zlib  zip xml \
#     intl ctype iconv bcmath fileinfo

RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo pdo_mysql

# Copy files
COPY . /var/www/html/

# # Expose ports
EXPOSE 80