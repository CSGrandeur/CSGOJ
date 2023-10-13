FLAG_CACHE=false
for arg in "$@"
do
    if [ "$arg" = "cache" ]; then
        FLAG_CACHE=true
    fi
done

if [ "$FLAG_CACHE" = "true" ]; then
    docker build -t csgrandeur/csgoj-judge .
else
    docker build --no-cache -t csgrandeur/csgoj-judge .
fi
