#!/bin/bash
# nginx and mysql should be started ahead, and baseoj should be inited
# bash start_judge.sh --OJ_NAME=csg --OJ_HTTP_BASEURL='http://nginx-server:20080' --PASS_JUDGER='987654321'  \
# other parameter
# --PATH_DATA=`pwd`/csgoj_data \
# --JUDGE_DOCKER_CPUS=2 \
# --JUDGE_DOCKER_MEMORY=4g \
# --JUDGE_PROCESS_NUM=2 \
# --JUDGER_TOTAL=1 \
# --OJ_MOD=0 

source parse_args.sh
parse_args "$@"

if [ "$WITH_JUDGE" = "true" ]; then
    echo "##################################################"
    echo "Initing judge"
    if [ "$JUDGER_TOTAL" -eq 1 ] && [ "$(docker ps -a -q -f name=/judge-$OJ_NAME$)" ]; then
        echo "judge-$OJ_NAME ready"
    else
        echo "JUDGER_TOTAL: $JUDGER_TOTAL"
        echo "JUDGER_IDX: $OJ_MOD"

        if [ ! -e "$PATH_DATA/var/data/judge-$OJ_NAME" ]; then
            mkdir -p $PATH_DATA/var/data/judge-$OJ_NAME
        fi

        if [ $JUDGER_TOTAL -gt 1 ]; then
            CHANGE_ETC="etc-$OJ_MOD"
            if [ -d $PATH_DATA/var/data/judge-$OJ_NAME/$CHANGE_ETC ]; then
                sudo rm -rf $PATH_DATA/var/data/judge-$OJ_NAME/$CHANGE_ETC
            fi
            mkdir -p $PATH_DATA/var/data/judge-$OJ_NAME/$CHANGE_ETC
            SIDE_ETC="-v $PATH_DATA/var/data/judge-$OJ_NAME/$CHANGE_ETC:/volume/etc"  # 这里要映射整个etc目录，因为etc下的judge.pid识别judge占用
            CONTAINER_NAME=judge-$OJ_NAME-$OJ_MOD
        else
            if [ -d $PATH_DATA/var/data/judge-$OJ_NAME/etc ]; then
                sudo rm -rf $PATH_DATA/var/data/judge-$OJ_NAME/etc
            fi
            SIDE_ETC=""
            CHANGE_ETC="etc"
            CONTAINER_NAME=judge-$OJ_NAME
        fi

        docker run -dit $LINK_LOCAL \
            --name $CONTAINER_NAME \
            -e OJ_HTTP_BASEURL="$OJ_HTTP_BASEURL" \
            -e OJ_HTTP_PASSWORD=$PASS_JUDGER \
            -e JUDGE_PROCESS_NUM=$JUDGE_PROCESS_NUM \
            -v $PATH_DATA/var/data/judge-$OJ_NAME:/volume $SIDE_ETC \
            --cpus=$JUDGE_DOCKER_CPUS \
            --memory=$JUDGE_DOCKER_MEMORY \
            --cap-add=SYS_PTRACE  \
            --restart unless-stopped \
            csgrandeur/csgoj-judge:$CSGOJ_VERSION

        echo "judge-$OJ_NAME inited"
    fi
fi