# bash release.sh 0.0.1 build push web judge
if [[ ! $1 =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
    echo "第一个参数应为 x.x.x 格式的版本号"
    exit 1
fi
TAG_VERSION=$1
FLAG_BUILD=false
FLAG_CACHE=""
FLAG_PUSH=false
FLAG_WEB=false
FLAG_JUDGE=false
PATH_DIR=`pwd`
for arg in "$@"
do
    if [ "$arg" = "build" ]; then
        FLAG_BUILD=true
    elif [ "$arg" = "push" ]; then
        FLAG_PUSH=true
    elif [ "$arg" = "cache" ]; then
        FLAG_CACHE="cache"
    elif [ "$arg" = "web" ]; then
        FLAG_WEB=true
    elif [ "$arg" = "judge" ]; then
        FLAG_JUDGE=true
    fi
done


if [ "$FLAG_BUILD" = "true" ]; then
    if [ "$FLAG_JUDGE" = "true" ]; then
        cd $PATH_DIR/build_judge && bash dockerbuild.sh $FLAG_CACHE
    fi
    if [ "$FLAG_WEB" = "true" ]; then
        cd $PATH_DIR/../ && bash dockerbuild.sh $FLAG_CACHE
    fi
fi


if [ "$FLAG_WEB" = "true" ]; then
    docker tag csgrandeur/csgoj-web:latest csgrandeur/csgoj-web:$TAG_VERSION
fi
if [ "$FLAG_JUDGE" = "true" ]; then
    docker tag csgrandeur/csgoj-judge:latest csgrandeur/csgoj-judge:$TAG_VERSION
fi

if [ "$FLAG_PUSH" = "true" ]; then
    if [ "$FLAG_WEB" = "true" ]; then
        docker push csgrandeur/csgoj-web:latest
        docker push csgrandeur/csgoj-web:$TAG_VERSION
    fi
    if [ "$FLAG_JUDGE" = "true" ]; then
        docker push csgrandeur/csgoj-judge:latest
        docker push csgrandeur/csgoj-judge:$TAG_VERSION
    fi
fi