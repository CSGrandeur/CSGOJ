
#!/bin/bash
# bash start_db.sh \
#     --PATH_DATA='`pwd`/csgoj_data' \
#     --PASS_SQL_ROOT=987654321 \
#     --PASS_SQL_USER=987654321 \
#     --SQL_USER=csgcpc \
#     --PORT_DB=20006

source parse_args.sh
parse_args "$@"

if [ "$WITH_MYSQL" = "true" ]; then
    echo "##################################################"
    echo "Initing db"
    if [ "$(docker ps -aq -f name=/db$)" ]; then
        echo "db ready"
    else
        mkdir -p  $PATH_DATA/var/mysql/mysql_config
        echo "[mysqld]
        max_connections=2000
        mysqlx_max_connections=800
        default-time-zone='+8:00'" > $PATH_DATA/var/mysql/mysql_config/oj_my.cnf
        chmod 644 $PATH_DATA/var/mysql/mysql_config/oj_my.cnf
        mkdir -p $PATH_DATA/var/mysql/mysql_init
        echo "GRANT ALL PRIVILEGES ON \`$SQL_USER\\_%\`.* TO '$SQL_USER'@'%';" > $PATH_DATA/var/mysql/mysql_init/init.sql;

        docker run -dit $LINK_LOCAL \
            --name db \
            -p $PORT_DB:3306 \
            -v $PATH_DATA/var/mysql/mysql_data:/var/lib/mysql \
            -v $PATH_DATA/var/mysql/mysql_config:/etc/mysql/conf.d \
            -v $PATH_DATA/var/mysql/mysql_init:/docker-entrypoint-initdb.d \
            -e MYSQL_ROOT_PASSWORD=$PASS_SQL_ROOT \
            -e MYSQL_USER=$SQL_USER \
            -e MYSQL_PASSWORD=$PASS_SQL_USER \
            --restart=unless-stopped \
            mysql:8.0.32 \
            --default-authentication-plugin=mysql_native_password

        echo "db inited"
    fi
fi