#!/bin/bash
PASSWORD_DEFAULT=987654321
# 输入参数：
CSGOJ_VERSION=latest
PATH_DATA=`pwd`/csgoj_data
WITH_JUDGE='false'
PASS_SQL_ROOT=$PASSWORD_DEFAULT
PASS_SQL_USER=$PASSWORD_DEFAULT
PASS_JUDGER=$PASSWORD_DEFAULT
PASS_ADMIN=$PASSWORD_DEFAULT
PASS_MYADMIN_PAGE=$PASSWORD_DEFAULT
PORT_OJ=20080
PORT_MYADMIN=20050
PORT_DB=20006
PORT_OJ_DB=3306
SQL_USER='csgcpc'
SQL_HOST='db'
WITH_MYSQL='true'
OJ_NAME='csgoj'
OJ_CDN='local'
OJ_MODE='cpcsys'
OJ_STATUS='cpc'
OJ_OPEN_OI='false'
OJ_UPDATE_STATIC='false'
BELONG_TO=false
JUDGE_DOCKER_CPUS=6
JUDGE_DOCKER_MEMORY=4g
JUDGE_PROCESS_NUM=2
JUDGE_IGNORE_ESOL=0
OJ_HTTP_BASEURL='http://nginx-server:20080'
JUDGER_TOTAL=1
OJ_MOD=0
NGINX_PORT_RANGS=''
SECRET_KEY='super_secret_oj'
DOCKER_NET_NAME="csgoj_net"
LINK_LOCAL="--network $DOCKER_NET_NAME"

parse_args() {
  shortopts=""
  longopts="
    CSGOJ_VERSION:, \
    PATH_DATA:, \
    WITH_JUDGE:, \
    PASS_SQL_ROOT:, \
    PASS_SQL_USER:, \
    PASS_JUDGER:, \
    PASS_ADMIN:, \
    PASS_MYADMIN_PAGE:, \
    SQL_USER:, \
    SQL_HOST:, \
    WITH_MYSQL:, \
    OJ_NAME:, \
    OJ_CDN:, \
    OJ_MODE:, \
    OJ_STATUS:, \
    OJ_OPEN_OI:, \
    OJ_UPDATE_STATIC:, \
    PORT_OJ:, \
    PORT_OJ_DB:, \
    PORT_MYADMIN:, \
    PORT_DB:, \
    BELONG_TO:, \
    JUDGE_DOCKER_CPUS:, \
    JUDGE_DOCKER_MEMORY:, \
    JUDGE_PROCESS_NUM:, \
    JUDGE_IGNORE_ESOL:, \
    OJ_HTTP_BASEURL:, \
    JUDGER_TOTAL:, \
    OJ_MOD:, \
    NGINX_PORT_RANGS:, \
    SECRET_KEY:, \
    DOCKER_NET_NAME:, \
    LINK_LOCAL:
  "
  opts=$(getopt -o "$shortopts" --long "$longopts" -n "$0" -- "$@")
  if [ $? != 0 ]; then
    echo "Terminating..."
    exit 1
  fi
  eval set -- "$opts"
  while true; do
    case "$1" in
        --CSGOJ_VERSION)                CSGOJ_VERSION="$2"; shift 2;;               # docker hub 中CSGOJ 版本号（tag）
        --PATH_DATA)                    PATH_DATA="$2"; shift 2;;                   # 所有系统文件与数据的存放目录，用绝对路径
        --WITH_JUDGE)                   WITH_JUDGE="$2"; shift 2;;                  # true / false
        --PASS_SQL_ROOT)                PASS_SQL_ROOT="$2"; shift 2;;               # sql root密码
        --PASS_SQL_USER)                PASS_SQL_USER="$2"; shift 2;;               # sql 业务用户密码
        --PASS_JUDGER)                  PASS_JUDGER="$2"; shift 2;;                 # judge判题账号的密码
        --PASS_ADMIN)                   PASS_ADMIN="$2"; shift 2;;                  # admin管理员密码
        --PASS_MYADMIN_PAGE)            PASS_MYADMIN_PAGE="$2"; shift 2;;           # phpmyadmin的页面权限admin的密码
        --SQL_USER)                     SQL_USER="$2"; shift 2;;                    # sql 业务用户名
        --SQL_HOST)                     SQL_HOST="$2"; shift 2;;                    # sql 地址
        --WITH_MYSQL)                   WITH_MYSQL="$2"; shift 2;;                  # 是否部署docker的mysql, true / false
        --OJ_NAME)                      OJ_NAME="$2"; shift 2;;                     # OJ名称
        --OJ_CDN)                       OJ_CDN="$2"; shift 2;;                      # OJCDN local or jsdelivr
        --OJ_MODE)                      OJ_MODE="$2"; shift 2;;                     # online or cpcsys
        --OJ_STATUS)                    OJ_STATUS="$2"; shift 2;;                   # cpc
        --OJ_OPEN_OI)                   OJ_OPEN_OI="$2"; shift 2;;                  # true or false
        --OJ_UPDATE_STATIC)             OJ_UPDATE_STATIC="$2"; shift 2;;            # 部署时是否替换公共static（datapath/baseoj/public/static/*）
        --PORT_OJ)                      PORT_OJ="$2"; shift 2;;                     # OJ web端口
        --PORT_OJ_DB)                   PORT_OJ_DB="$2"; shift 2;;                  # OJ web 连接数据库使用的端口
        --PORT_MYADMIN)                 PORT_MYADMIN="$2"; shift 2;;                # MYADMIN web端口
        --PORT_DB)                      PORT_DB="$2"; shift 2;;                     # DB 3306映射给外部的端口
        --BELONG_TO)                    BELONG_TO="$2"; shift 2;;                   # 新启动的OJ作为 $BELONG_TO 的同数据库同文件系统的另一功能的OJ
        --JUDGE_DOCKER_CPUS)            JUDGE_DOCKER_CPUS="$2"; shift 2;;           # docker限制judge的内核数，建议$JUDGE_PROCESS_NUM*3
        --JUDGE_DOCKER_MEMORY)          JUDGE_DOCKER_MEMORY="$2"; shift 2;;         # docker限制judge的内存
        --JUDGE_PROCESS_NUM)            JUDGE_PROCESS_NUM="$2"; shift 2;;           # judge并行判题进程数
        --JUDGE_IGNORE_ESOL)            JUDGE_IGNORE_ESOL="$2"; shift 2;;           # judge 1忽略/0不忽略 输出的行末空格，0为严格模式，易PE
        --OJ_HTTP_BASEURL)              OJ_HTTP_BASEURL="$2"; shift 2;;             # judge访问的OJ地址
        --JUDGER_TOTAL)                 JUDGER_TOTAL="$2"; shift 2;;                # judge多pod判题的机器数
        --OJ_MOD)                       OJ_MOD="$2"; shift 2;;                      # judge多pod判题的本机编号
        --NGINX_PORT_RANGS)             NGINX_PORT_RANGS="$2"; shift 2;;            # 给nginx映射更多端口，"-p 80-89:80-89 -p 20000-20100:20000-20100"
        --SECRET_KEY)                   SECRET_KEY="$2"; shift 2;;                  # 一些加密地方的key，不太重要
        --DOCKER_NET_NAME)              DOCKER_NET_NAME="$2"; shift 2;;             # docker局部网络名称
        --LINK_LOCAL)                   LINK_LOCAL="$2"; shift 2;;                  # docker局部网络设置，"--network $DOCKER_NET_NAME" 或空 ""
        --) shift; break;; 
        *) echo "Internal error!"; exit 1;; 
    esac
  done
  
  if [ -z "$(docker network ls | grep $DOCKER_NET_NAME)" ]; then
      docker network create $DOCKER_NET_NAME
  fi

  if [ ! -d $PATH_DATA ]; then
    mkdir -p $PATH_DATA
    sudo chown -R $USER $PATH_DATA
    chmod 777 -R $PATH_DATA
  fi 
  if [ "$BELONG_TO" = "false" ]; then
      BELONG_TO=$OJ_NAME
  fi
}
echo "arguments: $@"
