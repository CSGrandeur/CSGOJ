# /bin/bash
# 【本脚本杀伤力大，三思而行】
# 删除脚本第一个参数（题目id）之后的所有题目（但是不会删除solution、contest题单）
# bash rollback_problem.sh \
#     2000 \
#     --PASS_SQL_USER="987654321" \
#     --SQL_USER="csgcpc" \
#     --SQL_HOST="127.0.0.1" \
#     --SQL_PORT=3306 \
#     --OJ_NAME=test

PROBLEM_ID=$1
cd ..
source parse_args.sh
parse_args "$@"

if ! [[ $PROBLEM_ID =~ ^[0-9]+$ ]]; then
    echo "PROBLEM_ID should be number"
    exit 1
fi

DATA_FOLDER=/var/data/judge-$OJ_NAME/data
SQL_DB=$SQL_USER"_"$OJ_NAME
echo "=================================================="
echo "PROBLEM_ID:       $PROBLEM_ID"
echo "PASS_SQL_USER:    $PASS_SQL_USER"
echo "SQL_USER:         $SQL_USER"
echo "SQL_HOST:         $SQL_HOST"
echo "PORT_DB:          $PORT_DB"
echo "OJ_NAME:          $OJ_NAME"
echo "SQL_DB:           $SQL_DB"
echo "DATA_FOLDER:      $DATA_FOLDER"
echo "=================================================="
echo $PROBLEM_ID
echo $PASS_SQL_USER

read -r -p "Are You Sure?  Problem more than $PROBLEM_ID will be deleted [y/N]" input
case $input in
    [yY][eE][sS]|[yY])
# **********
    echo "===== deleting problem data folders ====="
    for dir in $DATA_FOLDER/*; do
        dirname=$(basename "$dir")
        # 如果目录名为数字且大于输入的数字，则删除该目录
        if [[ $dirname =~ ^[0-9]+$ ]] && [ $dirname -ge $PROBLEM_ID ]; then
            echo "DELETE: $dir"
            rm -rf $dir
        fi
    done
    echo "===== deleting sql problem items ====="
    MYSQL_CMD="mysql -u$SQL_USER -h $SQL_HOST -P $PORT_DB -p$PASS_SQL_USER -D $SQL_DB -e"
    CMD_DO="DELETE FROM \`problem\` WHERE problem_id >= $PROBLEM_ID;"
    echo $MYSQL_CMD \"$CMD_DO\"
    $MYSQL_CMD "$CMD_DO"
    CMD_DO="DELETE FROM \`problem_md\` WHERE problem_id >= $PROBLEM_ID;"
    echo $MYSQL_CMD \"$CMD_DO\"
    $MYSQL_CMD "$CMD_DO"
    CMD_DO="ALTER TABLE \`problem\` AUTO_INCREMENT=$PROBLEM_ID;"
    echo $MYSQL_CMD \"$CMD_DO\"
    $MYSQL_CMD "$CMD_DO"
# **********
 ;;
    *)
 ;;
esac


# alter table test auto_increment=1;
