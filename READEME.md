# git

- HTTPS
- remote -v
  - origin https://github.com/bt-ShigetaYusuke/laravel-docker.git (fetch)
  - origin https://github.com/bt-ShigetaYusuke/laravel-docker.git (push)

# 開発環境構成

Laravel + Docker + MySQL + phpMyAdmin
Nginx / PHP-FPM / MySQL / phpMyAdmin の 4 コンテナ構成

## ディレクトリ構成

```
laravel-docker/
├─ docker/
│  ├─ php/
│  │  └─ Dockerfile
│  └─ nginx/
│     └─ default.conf
└─ docker-compose.yml
```

# 開発環境起動

## プロジェクト直下で

```bash
# 初回ビルド＆起動
docker compose up -d --build

# Building 143.6s (13/13) FINISHED

# まだコードが空なら、コンテナ内で Laravel を作成
# /src ディレクトリを作成
docker compose exec app composer create-project laravel/laravel src

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
phpMyAdmin: http://localhost:3035

- サーバ: `mysql`
- ユーザー: `app_user`
- パスワード: `app_pass`

## 動作確認時のエラー

### 500 エラー

```
## エラー内容
500エラー
file_put_contents(/var/www/html/src/storage/framework/views/d97de7ecc368bef170ea6dcc183082c4.php): Failed to open stream: Permission denied

## 原因
それ、Laravelが storage と bootstrap/cache に書き込めなくて落ちてるやつ。

## 解決策
docker compose exec app sh -lc '
  cd /var/www/html/src && \
  mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache && \
  rm -f storage/framework/views/*.php bootstrap/cache/*.php 2>/dev/null || true && \
  chown -R www-data:www-data storage bootstrap/cache && \
  chmod -R ug+rwx storage bootstrap/cache
'

docker compose exec app php src/artisan optimize:clear
```

### UnexpectedValueException

```
## エラー内容
UnexpectedValueException
vendor/monolog/monolog/src/Monolog/Handler/StreamHandler.php:156

## 原因
storage/logs/laravel.log に 書き込み権限がなくて ログ出せない
ついでに SESSION_DRIVER=database で sessions テーブル未作成

## 解決策
権限を正す
docker compose exec app sh -lc '
  cd /var/www/html/src && \
  mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache && \
  touch storage/logs/laravel.log && \
  chown -R www-data:www-data storage bootstrap/cache && \
  chmod -R ug+rwx storage bootstrap/cache
'
```

### Illuminate\Database\QueryException

```
## エラー内容
Illuminate\Database\QueryException
vendor/laravel/framework/src/Illuminate/Database/Connection.php:824
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'app_db.sessions' doesn't exist (Connection: mysql, SQL: select * from `sessions` where `id` = MZ25KwW2JgeYi2SrpE1bx6odqaqdSJhQ00mOOqHh limit 1)

## 原因
セッションをDB保存にしてる（SESSION_DRIVER=database）のに、sessions テーブルが無いのが原因。
DBで運用したい。

## 解決策
1. マイグレーション作成（sessions テーブル定義を出す）（既に作成済みだったら次へ）
docker compose exec -u www-data app php src/artisan session:table

2. 反映（テーブル作成）
docker compose exec -u www-data app php src/artisan migrate

3. 念のためキャッシュ系クリア
docker compose exec -u www-data app php src/artisan optimize:clear
```

### mysql 側の権限

```
## エラー内容
テーブル作成等の権限がない？

## 原因

## 解決策

### 状態を確認
PS C:\workspace\laravel-docker> docker compose ps
NAME                          IMAGE                COMMAND                   SERVICE      CREATED          STATUS          PORTS
laravel-docker-app-1          laravel-docker-app   "docker-php-entrypoi…"   app          35 minutes ago   Up 35 minutes   9000/tcp
laravel-docker-mysql-1        mysql:8.0            "docker-entrypoint.s…"   mysql        4 minutes ago    Up 4 minutes    0.0.0.0:2025->3306/tcp, [::]:2025->3306/tcp
laravel-docker-phpmyadmin-1   phpmyadmin:latest    "/docker-entrypoint.…"   phpmyadmin   37 minutes ago   Up 37 minutes   0.0.0.0:3035->80/tcp, [::]:3035->80/tcp
laravel-docker-web-1          nginx:alpine         "/docker-entrypoint.…"   web          37 minutes ago   Up 25 minutes   0.0.0.0:1025->80/tcp, [::]:1025->80/tcp
PS C:\workspace\laravel-docker>
PS C:\workspace\laravel-docker> docker inspect $(docker compose ps -q mysql) --format '{{json .Config.Env}}' | jq
[
  "MYSQL_DATABASE=app_db",
  "MYSQL_USER=app_user",
  "MYSQL_PASSWORD=app_pass",
  "TZ=Asia/Tokyo",
  "MYSQL_ROOT_PASSWORD=root",
  "MYSQL_ROOT_HOST=%",
  "PATH=/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin",
  "GOSU_VERSION=1.19",
  "MYSQL_MAJOR=8.0",
  "MYSQL_VERSION=8.0.44-1.el9",
  "MYSQL_SHELL_VERSION=8.0.44-1.el9"
]
PS C:\workspace\laravel-docker>
PS C:\workspace\laravel-docker> docker compose exec mysql mysql -uroot -proot -e "SELECT host,user FROM mysql.user;"
>>
mysql: [Warning] Using a password on the command line interface can be insecure.
+-----------+------------------+
| host      | user             |
+-----------+------------------+
| %         | app_user         |
| %         | root             |
| localhost | mysql.infoschema |
| localhost | mysql.session    |
| localhost | mysql.sys        |
| localhost | root             |
+-----------+------------------+

### .evnの内容確認
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=app_db
DB_USERNAME=app_user
DB_PASSWORD=app_pass

# セッションをDBで使うなら
SESSION_DRIVER=database
# すぐ動かしたいなら一旦 file でもOK → SESSION_DRIVER=file

### .evnの内容を反映
docker compose exec -u www-data app php src/artisan config:clear

### セッション/キャッシュのテーブルを用意
docker compose exec -u www-data app php src/artisan cache:table
docker compose exec -u www-data app php src/artisan session:table  # 既にあるならOK、無ければ作られる
docker compose exec -u www-data app php src/artisan migrate
```

# mac で開発環境構築

## git clone

```bash
git clone https://github.com/bt-ShigetaYusuke/laravel-docker.git

git remote add origin https://github.com/bt-ShigetaYusuke/laravel-docker.git

# 最新ブランチに切り替え
```

## プロジェクト直下で

```bash
# 初回ビルド＆起動
docker compose up -d --build

# .env を編集
cp .env.example .env

docker compose exec app bash

cd /var/www/html/src

composer install

exit

docker compose exec app php src/artisan key:generate
docker compose exec app php src/artisan migrate
```

- [動作確認](#動作確認)
