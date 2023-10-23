#!/bin/bash
# bash start_nginx.sh \
#   --PATH_DATA='`pwd`/csgoj_data' \
#   --PORT_OJ=20080 \
#   --PORT_MYADMIN=20050
source parse_args.sh
parse_args "$@"

echo "##################################################"
echo "Initing nginx"
if [ "$(docker ps -aq -f name=/nginx-server$)" ]; then
    echo "nginx-server ready"
else
  if [ -z "$NGINX_PORT_RANGS" ]; then
    NGINX_PORT_RANGS="-p $PORT_OJ:$PORT_OJ -p $PORT_MYADMIN:$PORT_MYADMIN"
  fi
  PUBLIC_MOUNT=""
  if [ "$CSGOJ_DEV" = "1" ];then
    PUBLIC_MOUNT="-v `pwd`/../ojweb/public:/var/www/baseoj/public"
  fi
  docker run --name nginx-server $LINK_LOCAL \
    $NGINX_PORT_RANGS \
    -v $PATH_DATA/var/www:/var/www $PUBLIC_MOUNT \
    -v $PATH_DATA/dataspace:$PATH_DATA/dataspace \
    -v $PATH_DATA/var/log/nginx:/var/log/nginx \
    -v $PATH_DATA/nginx/nginx_conf.d:/etc/nginx/conf.d \
    -v $PATH_DATA/nginx/attach:/etc/nginx/attach \
    --restart=unless-stopped \
    -d nginx:1.25.2-alpine
    echo "nginx-server inited"
fi