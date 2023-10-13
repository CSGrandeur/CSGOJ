# php
# php 7.4 not support thinkphp5.0, so 7.3

# # if you configured a proxy for original alpine
# docker build --network host \
# --build-arg http_proxy=http://127.0.0.1:10809 \
# --build-arg https_proxy=http://127.0.0.1:10809 \
# -t php:fpm-alpine-modify .

# this Dockerfile already replaced source to tuna tsinghua.
# docker build -t php:fpm-alpine-modify .

# for local pandoc downloaded
docker build -t php:fpm-alpine-modify -f Dockerfile_withpandoctar .
