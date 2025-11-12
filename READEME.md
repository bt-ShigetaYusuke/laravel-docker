# 開発環境構成

Laravel + Docker + MySQL + phpMyAdmin
Nginx / PHP-FPM / MySQL / phpMyAdmin の 4 コンテナ構成

## ディレクトリ構成

```
my-laravel/
├─ docker/
│  ├─ php/
│  │  └─ Dockerfile
│  └─ nginx/
│     └─ default.conf
└─ docker-compose.yml
```

## docker-compose.yml

```yml
services:
  app:
    build:
      context: ./docker/php
    volumes:
      - ./:/var/www/html
    depends_on:
      - mysql

  web:
    image: nginx:alpine
    ports:
      - "1025:80" # → http://localhost:1025 でLaravel表示
    volumes:
      - ./:/var/www/html
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf:ro
    depends_on:
      - app

  mysql:
    image: mysql:8.0
    command: --default-authentication-plugin=mysql_native_password
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: app_db
      MYSQL_USER: app_user
      MYSQL_PASSWORD: app_pass
      TZ: Asia/Tokyo
    ports:
      - "2025:3306"
    volumes:
      - dbdata:/var/lib/mysql

  phpmyadmin:
    image: phpmyadmin:latest
    environment:
      PMA_HOST: mysql
      PMA_USER: app_user
      PMA_PASSWORD: app_pass
      UPLOAD_LIMIT: 256M
    ports:
      - "2025:80" # → http://localhost:2025 でphpMyAdmin

volumes:
  dbdata:
```

## docker/php/Dockerfile

```dockerfile
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
RUN echo "memory_limit=512M\nupload_max_filesize=64M\npost_max_size=64M\nmax_execution_time=120\n" > /usr/local/etc/php/conf.d/dev.ini

WORKDIR /var/www/html
```

## docker/nginx/default.conf

```nginx
server {
    listen 80;
    server_name localhost;
    root /var/www/html/public;
    index index.php index.html;

    charset utf-8;
    client_max_body_size 64M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~* \.(css|js|jpg|jpeg|png|gif|webp|svg|ico|woff2?)$ {
        expires 7d;
        access_log off;
    }
}
```

# 開発環境起動

## プロジェクト直下で

```bash
# 初回ビルド＆起動
docker compose up -d --build

# まだコードが空なら、コンテナ内で Laravel を作成
docker compose exec app composer create-project laravel/laravel .

# .env を編集（下の例を参考に）
cp .env.example .env
```

## env

```env
APP_NAME="Laravel"
APP_ENV=local
APP_KEY=   # 後で生成する
APP_DEBUG=true
APP_URL=http://localhost:8080

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=app_db
DB_USERNAME=app_user
DB_PASSWORD=app_pass

# タイムゾーン（お好みで）
TZ=Asia/Tokyo
```

## キー生成＆マイグレーション

```bash
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate
```

# 動作確認

Laravel: http://localhost:1025
phpMyAdmin: http://localhost:2025

- サーバ: `mysql`
- ユーザー: `app_user`
- パスワード: `app_pass`
