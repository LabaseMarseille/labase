FROM mariadb
COPY init-user-db.sql /docker-entrypoint-initdb.d/
