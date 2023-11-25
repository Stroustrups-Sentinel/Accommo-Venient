# accommo_venientdb.sql

FROM ubuntu:latest

# Install dependencies
RUN apt-get update && apt-get install -y wget

# Download XAMPP installer
RUN wget https://sourceforge.net/projects/xampp/files/XAMPP%20Linux/8.0.30/xampp-linux-x64-8.0.30-0-installer.run

# Make installer executable 
RUN chmod +x xampp-linux-x64-8.0.30-0-installer.run

# Install XAMPP
RUN ./xampp-linux-x64-8.0.30-0-installer.run -q

# Expose Apache and MySQL ports
EXPOSE 80 3306

# Copy SQL script
COPY accommo_venientdb.sql /opt/lampp/

# Start servers
CMD /opt/lampp/xampp start && \
    mysql -u root < /opt/lampp/accommo_venientdb.sql
