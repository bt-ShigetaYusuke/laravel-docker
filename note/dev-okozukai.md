# 概要

おこずかいアプリを作る
銀行紐付けなどはなく、単純に数値を扱うだけ
目的は貯金

# url

## main

[main-okozukai-top](https://yusuke-shigeta.com/okozukai)

## dev

[dev-okozukai-top](http://localhost:1025/okozukai)

# 画面

## 画面一覧

- [/okozukai] トップページ
- [/okozukai/balance] 残高画面
- [/okozukai/spend] 支出画面
- [/okozukai/history] 履歴画面

## 画面遷移図

### [/okozukai] トップページ

```md
[/okozukai] に直接アクセス
ヘッダーロゴからアクセス
```

### [/okozukai/balance] 残高画面

```md
共通フッターからアクセス
```

### [/okozukai/history] 履歴画面

```md
共通フッターからアクセス
```

## 要素

### [/okozukai] トップページ

- 共通ヘッダー
- 共通フッター
- 今月の支出合計
- 支出入力フォーム
  - input 支出入力
  - btn 保存

### [/okozukai/balance] 残高画面

- 共通ヘッダー
- 共通フッター
- 今月残り金額
- 貯金額

### [/okozukai/history] 履歴画面

- 共通ヘッダー
- 共通フッター
- 収支履歴テーブル（カテゴリ振り分け機能付き）

# 機能

## 機能一覧

- 支出入力機能
  - 支出入力 → 保存 → 今月の支出合計に加算
  - 支出入力 → 保存 → 今月残り金額から減算
- 今月の支出合計リセット
  - 月初に 0 にリセット
- 今月残り金額をリセット
  - 月初に設定額にリセット
  - 最初は 3 万円で設定する
- 貯金額
  - 貯金額 = 今月残り金額の値が毎月加算されていくマイナスはない。
    - 1 月 : 3 万円のうち 1 万円残った場合、 貯金額 = 10,000 円
    - 2 月 : 3 万円のうち 8 千円残った場合、 貯金額 = 18,000 円
    - 3 月 : 3 万円のうち 1.2 万円残った場合、 貯金額 = 30,000 円
    - ...
- 収支履歴テーブル
  - カテゴリ振り分け機能付き
  - 支出取消機能付き
    - 支出合計, 今月残り金額が戻る想定
  - 月別、カテゴリ別検索あり

## 機能フローチャート

# テーブル定義書

## okozukai_expenses

### テーブル概要

- テーブル名（物理） : okozukai_expenses
- テーブル名（論理） : おこづかい支出情報
- 用途 : 支出履歴を保存し、月の支出合計・残高計算などに使用する

### カラム定義

1. id
   - [LogicalName] ID
   - [Type-------] bigint, unsigned
   - [Length/Size] -
   - [NULL-------] NG
   - [Key--------] PK
   - [Default----] auto_increment
2. spent_at
   - [LogicalName] 支出日
   - [Type-------] date
   - [Length/Size] -
   - [NULL-------] NG
   - [Key--------] -
   - [Default----] -
3. amount
   - [LogicalName] 金額
   - [Type-------] int
   - [Length/Size] -
   - [NULL-------] NG
   - [Key--------] -
   - [Default----] -
4. okozukai_category_id
   - [LogicalName] カテゴリ ID
   - [Type-------] bigint, unsigned
   - [Length/Size] -
   - [NULL-------] OK
   - [Key--------] FK
   - [Default----] NULL
5. created_at
   - [LogicalName] 作成日時
   - [Type-------] timestamp
   - [Length/Size] -
   - [NULL-------] OK
   - [Key--------] -
   - [Default----] NULL
6. updated_at
   - [LogicalName] 更新日時
   - [Type-------] timestamp
   - [Length/Size] -
   - [NULL-------] OK
   - [Key--------] -
   - [Default----] NULL

## okozukai_categories

### テーブル概要

- テーブル名（物理） : okozukai_categories
- テーブル名（論理） : おこづかいカテゴリ情報
- 用途 : 支出をカテゴリ分けするために使用（食費・遊び・交通など）

### カラム定義

1. id
   - [LogicalName] ID
   - [Type-------] bigint, unsigned
   - [Length/Size] -
   - [NULL-------] NG
   - [Key--------] PK
   - [Default----] auto_increment
2. name
   - [LogicalName] カテゴリ名
   - [Type-------] varchar(100)
   - [Length/Size] 100
   - [NULL-------] NG
   - [Key--------] -
   - [Default----] -
3. created_at
   - [LogicalName] 作成日時
   - [Type-------] timestamp
   - [Length/Size] -
   - [NULL-------] OK
   - [Key--------] -
   - [Default----] -
4. updated_at
   - [LogicalName] 更新日時
   - [Type-------] timestamp
   - [Length/Size] -
   - [NULL-------] OK
   - [Key--------] -
   - [Default----] -

## okozukai_monthly_summaries

### テーブル概要

- テーブル名（物理） : okozukai_monthly_summaries
- テーブル名（論理） : おこづかい月次サマリ情報
- 用途 : 各月ごとの支出合計・残り金額・貯金額（積み上げ）を集計・保存するためのテーブル
  - 月末（or 月初）に 1 回だけ更新
  - 最新レコードの total_saving が「現在の貯金額」として利用される

### カラム定義

1. id
   - [LogicalName] ID
   - [Type-------] bigint, unsigned
   - [Length/Size] -
   - [NULL-------] NG
   - [Key--------] PK
   - [Default----] auto_increment
2. year_month
   - [LogicalName] 対象年月
   - [Type-------] char(7)
   - [Length/Size] 7
   - [NULL-------] NG
   - [Key--------] UK
   - [Default----] -
3. budget
   - [LogicalName] 月の予算額（月に使ってよい最大金額）
   - [Type-------] int
   - [Length/Size] -
   - [NULL-------] NG
   - [Key--------] -
   - [Default----] -
4. total_spent
   - [LogicalName] 月の支出合計
   - [Type-------] int
   - [Length/Size] -
   - [NULL-------] NG
   - [Key--------] -
   - [Default----] 0
5. remaining
   - [LogicalName] 月の残り金額
   - [Type-------] int
   - [Length/Size] -
   - [NULL-------] NG
   - [Key--------] -
   - [Default----] 0
6. saving_added
   - [LogicalName] 今月の貯金追加額（その月の「残り金額」がそのまま貯金になる）
   - [Type-------] int
   - [Length/Size] -
   - [NULL-------] NG
   - [Key--------] -
   - [Default----] 0
7. total_saving
   - [LogicalName] 貯金累計額（今月までの貯金の合計値）
   - [Type-------] int
   - [Length/Size] -
   - [NULL-------] NG
   - [Key--------] -
   - [Default----] 0
8. created_at
   - [LogicalName] 作成日時
   - [Type-------] timestamp
   - [Length/Size] -
   - [NULL-------] OK
   - [Key--------] -
   - [Default----] NULL
9. updated_at
   - [LogicalName] 更新日時
   - [Type-------] timestamp
   - [Length/Size] -
   - [NULL-------] OK
   - [Key--------] -
   - [Default----] NULL

# 変数定義書

# テストケース

# 作業

- [ ] マイグレーション
- [ ] モデル
- [ ] ルーティング
- [ ] トップページ
  - コントローラー
  - ビュー
- [ ] 支出画面
  - コントローラー
  - ビュー
- [ ] 履歴画面
  - コントローラー
  - ビュー
- 月初ボタンで貯金管理
- ユーザー認証

## マイグレーション

```
docker compose exec app bash
↓
cd src
↓
php artisan make:migration create_okozukai_expenses_table
php artisan make:migration create_okozukai_categories_table
php artisan make:migration create_okozukai_monthly_summaries_table
↓
マイグレーション記述
↓
マイグレーション順をファイル名リネームして調整
↓
php artisan migrate
```

## モデル

```
作る
php artisan make:model OkozukaiExpense
php artisan make:model OkozukaiCategory
php artisan make:model OkozukaiMonthlySummary
↓
モデルにリレーション貼る
```

## ルーティング

## コントローラー

```
作る
php artisan make:controller OkozukaiController
php artisan make:controller OkozukaiBalanceController
php artisan make:controller OkozukaiHistoryController
```

## ユーザー認証

1. Laravel に「認証まわりのひな形」を入れる（Breeze 使うのが一番ラクらしい）
2. /login / /register 画面を自動生成
3. ログインしてないと /okozukai 系に入れないようにする
4. ログイン後の遷移先を /okozukai にする

```md
# 1. 認証ひな形を入れる（Laravel Breeze）

1. コンテナ入る
   docker compose exec app bash
   cd src

2. Breeze インストール
   composer require laravel/breeze --dev

3. Blade 版で scaffolding 生成
   php artisan breeze:install blade

   /login
   /register
   /forgot-password
   /reset-password
   とか一式、routes・controller・view がドバッと生える。

4. フロントのビルド
   npm run dev
   失敗したら
   npm install

5. マイグレーション users テーブル
   php artisan migrate

# 2. ログイン後の遷移先を「おこづかいトップ」に変える

デフォだと /dashboard に飛ぶようになってるから、
これを /okozukai に変える。

[src/app/Http/Controllers/Auth/AuthenticatedSessionController.php] [store] でリダイレクト先変更

これで：
ログイン直後
新規登録直後
のリダイレクト先が /okozukai になる ✨

# 3. おこづかい画面を「ログイン必須」にする

routes/web.php の okozukai グループを auth ミドルウェアで囲む。

Route::middleware('auth')->prefix('okozukai')->name('okozukai.')->group(function () {
Route::get('/', [OkozukaiController::class, 'index'])->name('index');
Route::post('/spend', [OkozukaiController::class, 'store'])->name('spend');

    Route::get('/balance', [OkozukaiBalanceController::class, 'index'])->name('balance');

    Route::get('/history', [OkozukaiHistoryController::class, 'index'])->name('history');
    Route::delete('/history/{expense}', [OkozukaiHistoryController::class, 'destroy'])->name('history.destroy');

    Route::post('/balance/monthly-close', [OkozukaiBalanceController::class, 'monthlyClose'])
        ->name('balance.monthly_close');

});

これで：

ログインしてない人が /okozukai 開く
→ 自動で /login に飛ばされる
ログインすると /okozukai に戻ってくる

って流れになる。

# 4. 画面からログイン/ログアウトできるようにしとく

Breeze がヘッダーやナビを用意してくれてるから、
レイアウトを resources/views/layouts/app.blade.php に寄せていくのがおすすめ。

とりあえず「どっかにログアウトボタン欲しいな〜」ってなったら、
おこづかいのレイアウトにこれ足せば OK👇

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">ログアウト</button>
</form>

# 5. ログイン画面の URL 確認

Breeze 入れたら、標準でこれが使えるようになってる 👇

ログイン … /login
新規登録 … /register

ブラウザで叩いて、画面出るかチェックしてみてー。

ここまでできたら…

未ログインだと /okozukai 系に入れない
ログインすると /okozukai に飛ぶ
1 ユーザーごとのおこづかい管理に育てていける

ってとこまで来てる

ユーザーごとにおこづかい別管理（テーブルに user_id カラム足す）にしたい
```

# 変更ファイル

```
git diff --name-only origin/main
```

```
note/dev-okozukai.md
src/app/Http/Controllers/OkozukaiBalanceController.php
src/app/Http/Controllers/OkozukaiController.php
src/app/Http/Controllers/OkozukaiHistoryController.php
src/app/Models/OkozukaiCategory.php
src/app/Models/OkozukaiExpense.php
src/app/Models/OkozukaiMonthlySummary.php
src/database/migrations/2025_11_25_151000_create_okozukai_categories_table.php
src/database/migrations/2025_11_25_151029_create_okozukai_expenses_table.php
src/database/migrations/2025_11_25_151037_create_okozukai_monthly_summaries_table.php
src/resources/views/okozukai/balance.blade.php
src/resources/views/okozukai/history.blade.php
src/resources/views/okozukai/index.blade.php
src/routes/web.ph
```
