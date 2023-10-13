#!/bin/bash
# simple run:  bash auto_deploy.sh
# parameter run: # bash auto_deploy.sh --<PARAM>=<your param>  # see parse_args.sh
# 如果 --WITH_JUDGE=true，且PORT_OJ不是默认值，则要提供 --OJ_HTTP_BASEURL 参数告诉评测机 OJ在哪
# 例:
# bash auto_deploy.sh \
#     --PATH_DATA=`pwd`/csgoj_data \
#     --WITH_JUDGE=false \
#     --WITH_MYSQL=true \
#     --PASS_SQL_ROOT="123456" \
#     --PASS_SQL_USER="123456789" \
#     --PASS_JUDGER="999999" \
#     --PASS_ADMIN="666666" \
#     --PASS_MYADMIN_PAGE="333333" \
#     --SQL_USER="csgoj" \
#     --SQL_HOST="db" \
#     --PORT_OJ=80 \
#     --PORT_MYADMIN=8080 \
#     --PORT_DB=33306 \
#     --PORT_OJ_DB=3306

source parse_args.sh
parse_args "$@"

bash install_docker.sh
if [ "$WITH_MYSQL" = "true" ]; then
    bash start_db.sh "$@"
fi
bash start_myadmin.sh "$@"
bash start_oj.sh "$@"
bash start_nginx.sh "$@"
if [ "$WITH_JUDGE" = "true" ]; then
    bash start_judge.sh "$@"
fi

echo "容器已启动，初始化需要一定时间，可用 docker logs <容器名> 查看启动状态"
echo "例如： docker logs php-$OJ_NAME"
