# 批量启动一系列判题端
# bash batch_sub_judge.sh \
#   --PATH_DATA=`pwd`/csgoj_data \
#   --OJ_NAME=test \
#   --OJ_HTTP_BASEURL=http://url:20080 \
#   --PASS_JUDGER=987654321 \
#   --JUDGER_TOTAL=10 

source parse_args.sh
parse_args "$@"

echo "OJ_NAME: $OJ_NAME"
echo "OJ_HTTP_BASEURL: $OJ_HTTP_BASEURL"
echo "PASS_JUDGER: $PASS_JUDGER"
echo "JUDGER_TOTAL: $JUDGER_TOTAL"

for ((i=0; i<$JUDGER_TOTAL; i++))
do
    bash start_judge.sh \
        --PATH_DATA=$PATH_DATA \
        --OJ_NAME=$OJ_NAME \
        --OJ_HTTP_BASEURL="$OJ_HTTP_BASEURL" \
        --PASS_JUDGER="$PASS_JUDGER" \
        --JUDGER_TOTAL=$JUDGER_TOTAL \
        --OJ_MOD=$i
done

wait

# 批量重启： docker ps -a --filter "name=judgeprefix" -q | xargs docker restart
# 批量删除： docker ps -a --filter "name=judgeprefix" -q | xargs docker rm -f