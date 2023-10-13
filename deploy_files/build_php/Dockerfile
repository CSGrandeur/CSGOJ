FROM php:7.3.33-fpm-alpine
MAINTAINER csgrandeur <csgrandeur@gmail.com>
# RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.tuna.tsinghua.edu.cn/g' /etc/apk/repositories
RUN sed -i 's/dl-cdn.alpinelinux.org/mirrors.tencent.com/g' /etc/apk/repositories
ADD ./php.ini-production $PHP_INI_DIR/php.ini
RUN cat $PHP_INI_DIR/php.ini
ENV PANDOC_VERSION="3.1.8"
RUN apk add freetype freetype-dev
RUN apk add libpng libpng-dev
RUN apk add libjpeg-turbo libjpeg-turbo-dev
RUN apk add zip libzip-dev
RUN docker-php-ext-configure gd \
        --with-freetype-dir=/usr/include/ \
        --with-jpeg-dir=/usr/include/
RUN wget https://github.com/jgm/pandoc/releases/download/${PANDOC_VERSION}/pandoc-${PANDOC_VERSION}-linux-amd64.tar.gz \
    && tar xvzf pandoc-${PANDOC_VERSION}-linux-amd64.tar.gz --strip-components 2 -C /usr/local/bin/ \
    && rm pandoc-${PANDOC_VERSION}-linux-amd64.tar.gz \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip
RUN apk del freetype-dev libpng-dev libjpeg-turbo-dev
RUN rm /var/cache/apk/*
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql
