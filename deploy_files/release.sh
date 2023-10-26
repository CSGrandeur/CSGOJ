# bash release.sh 0.0.1 build push web judge
# 读取版本文件
current_version=$(cat version)

# 检查参数是否为版本号
if [[ ! $1 =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
    IFS='.' read -ra ADDR <<< "$current_version"
    minor_version=${ADDR[2]}
    minor_version=$((minor_version+1))
    TAG_VERSION="${ADDR[0]}.${ADDR[1]}.$minor_version"
else
    TAG_VERSION=$1
fi

# 版本号比较函数
function version_gt() { test "$(printf '%s\n' "$@" | sort -V | head -n 1)" != "$1"; }

# 比较新旧版本号
if version_gt $current_version $TAG_VERSION; then
    echo "参数版本号 $TAG_VERSION 低于现有版本号 $current_version"
    exit 1
fi

echo "即将处理版本号：$TAG_VERSION"

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

echo $TAG_VERSION > version