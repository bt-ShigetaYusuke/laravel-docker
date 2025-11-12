FROM php:8.3-fpm-alpine

# 必要ライブラリ & PHP拡張
RUN apk add --no-cache \
      bash git unzip icu-dev libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev oniguruma-dev \
      libxml2-dev zlib-dev curl-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
  && docker-php-ext-install -j$(nproc) \
      pdo_mysql mbstring exif pcntl bcmath intl gd opcache

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 推しの設定(お好みで)
# RUN echo "memory_limit=512M\nupload_max_filesize=64M\npost_max_size=64M\nmax_execution_time=120\n" > /usr/local/etc/php/conf.d/dev.ini

WORKDIR /var/www/html