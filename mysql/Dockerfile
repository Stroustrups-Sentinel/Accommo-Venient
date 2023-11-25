# You can change this to a newer version of MySQL available at
# https://hub.docker.com/r/mysql/mysql-server/tags/
FROM mysql/mysql-server:5.7

ENV MYSQL_USER=root
ENV MYSQL_ROOT_PASSWORD=

# Copy SQL file
COPY ../accommo_venientdb.sql /docker-entrypoint-initdb.d/

EXPOSE 3306

# Add timezone data
RUN yum install -y tzdata && \
    yum clean all && \
    rm -rf /var/cache/yum

# COPY config/user.cnf /etc/mysql/my.cnf