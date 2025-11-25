# url

# 画面

## 画面一覧

- [/memos] メモ一覧画面
- [/memos/create] 新規メモ作成画面
- [/memos/{memo}] メモ詳細画面
- [/memos/{memo}/edit] メモ編集画面

## 画面遷移図

### [/memos] メモ一覧画面

```md
[/memos]に直接アクセス
```

### [/memos/create] 新規メモ作成画面

```md
共通ヘッダーの [新規作成] リンク押下
[/memos] メモ一覧画面
[/memos/create] 新規メモ作成画面
[/memos/{memo}] メモ詳細画面
[/memos/{memo}/edit] メモ編集画面
```

### [/memos/{memo}] メモ詳細画面

```md
[/memos] メモ一覧画面を開く
↓
[memo] リンクを押下でアクセス
```

### [/memos/{memo}/edit] メモ編集画面

```md
[/memos/{memo}] メモ詳細画面を開く
↓
[編集] リンク押下でアクセス
```

# 機能

## 機能一覧

- [store] 登録処理
- [update] 更新処理
- [destroy] 削除処理

## 機能フローチャート

### [store] 登録処理

```md
[/memos/create] 新規メモ作成画面 を開く
↓
必須項目を入力
↓
[保存] を押下
↓
バリデーション
↓
↓ → [NG] エラーメッセージを表示
↓
[OK] DB に保存
↓
[/memos] メモ一覧画面 にリダイレクト
↓
Flash (success) メッセージを表示
```

### [update] 更新処理

```md
[/memos/{memo}/edit] メモ編集画面 を開く
↓
必須項目を入力
↓
[保存] を押下
↓
↓ → [NG] エラーメッセージを表示
↓
[OK] DB 更新
↓
[/memos] メモ一覧画面 にリダイレクト
↓
Flash (success) メッセージを表示
```

### [destroy] 削除処理

```md
[/memos/{memo}] メモ詳細画面 を開く
↓
[削除] リンクを押下
↓
確認ダイアログが表示される
↓
↓ → [キャンセル] → [削除] リンクを押下する前に戻る
↓
[OK] DB からレコードを直接削除
↓
[/memos] メモ一覧画面 にリダイレクト
↓
Flash (success) メッセージを表示
```

# テーブル定義書

## memos

### テーブル概要

- テーブル名（物理） : memos
- テーブル名（論理） : メモ情報
- 用途 : ユーザーが作成したメモを保存するテーブル

### カラム定義

1. id
   - [LogicalName] ID
   - [Type-------] bigint, unsigned
   - [Length/Size] -
   - [NULL-------] NG
   - [Key--------] PK
   - [Default----] auto_increment
2. title
   - [LogicalName] タイトル
   - [Type-------] varchar(100)
   - [Length/Size] 100
   - [NULL-------] NG
   - [Key--------] -
   - [Default----] -
3. content
   - [LogicalName] 内容
   - [Type-------] text
   - [Length/Size] -
   - [NULL-------] OK
   - [Key--------] -
   - [Default----] -
4. created_at
   - [LogicalName] 作成日時
   - [Type-------] timestamp
   - [Length/Size] -
   - [NULL-------] OK
   - [Key--------] -
   - [Default----] NULL
5. updated_at
   - [LogicalName] 更新日時
   - [Type-------] timestamp
   - [Length/Size] -
   - [NULL-------] OK
   - [Key--------] -
   - [Default----] NULL

### キー・インデックス情報

- PK：id
- FK：なし
- インデックス：特になし（必要なら title に追加検討）

### 補足

- Laravel の timestamps による更新管理
- 論理削除（deleted_at）は無し
- タイトルは 100 文字まで
- content は長文対応の text 型
- bigint, unsigned = 0 ~ でかい正の数
- PK = Primary Key = 重複禁止, NULL 禁止, そのレコードを一意に特定できる
- auto_increment = レコードを追加するたびに数字を自動で増やしてくれる仕組み

# 変数定義書

- [$q]
  - [LogicalName] クエリパラメーター
  - [Type-------] string
  - [Purpose----] 検索キーワード
- [$memos]
  - [LogicalName] メモ一覧（検索条件つき）のコレクション
  - [Type-------] object
  - [Purpose----] 一覧表示用のメモデータ
- [$memo]
  - [LogicalName] 単体 Memo
  - [Type-------]
  - [Purpose----] ルートモデルバインディングで渡される単体 Memo
- [$request]
  - [LogicalName] HTTP リクエスト
  - [Type-------]
  - [Purpose----]
- [$validated]
  - [LogicalName] バリデーション済みの入力データ
  - [Type-------]
  - [Purpose----]

# テストケース

## 画面遷移系

### [/memos] メモ一覧画面

- [ ] A-1
  - [Steps-] 共通ヘッダーの [Memo] リンク押下
  - [Result] [/memos] メモ一覧画面へ遷移すること
- [ ] A-2
  - [Steps-] 共通ヘッダーの [新規作成] リンク押下
  - [Result] [/memos/create] 新規メモ作成画面へ遷移すること
- [ ] A-3
  - [Steps-] アイテムリンク押下
  - [Result] [/memos/{memo}] メモ詳細画面へ遷移すること

### [/memos/create] 新規メモ作成画面

- [ ] A-1
  - [Steps-] 共通ヘッダーの [Memo] リンク押下
  - [Result] [/memos] メモ一覧画面へ遷移すること
- [ ] A-2
  - [Steps-] 共通ヘッダーの [新規作成] リンク押下
  - [Result] [/memos/create] 新規メモ作成画面へ遷移すること
- [ ] A-3
  - [Steps-] [戻る] リンク押下
  - [Result] [/memos] メモ一覧画面へ遷移すること

### [/memos/{memo}] メモ詳細画面

- [ ] A-1
  - [Steps-] 共通ヘッダーの [Memo] リンク押下
  - [Result] [/memos] メモ一覧画面へ遷移すること
- [ ] A-2
  - [Steps-] 共通ヘッダーの [新規作成] リンク押下
  - [Result] [/memos/create] 新規メモ作成画面へ遷移すること
- [ ] A-3
  - [Steps-] [編集] リンク押下
  - [Result] [/memos/{memo}/edit] メモ編集画面へ遷移すること
- [ ] A-4
  - [Steps-] [一覧へ] リンク押下
  - [Result] [/memos] メモ一覧画面へ遷移すること

### [/memos/{memo}/edit] メモ編集画面

- [ ] A-1
  - [Steps-] 共通ヘッダーの [Memo] リンク押下
  - [Result] [/memos] メモ一覧画面へ遷移すること
- [ ] A-2
  - [Steps-] 共通ヘッダーの [新規作成] リンク押下
  - [Result] [/memos/create] 新規メモ作成画面へ遷移すること
- [ ] A-3
  - [Steps-] [戻る] リンク押下
  - [Result] [/memos] メモ一覧画面へ遷移すること

## 検索機能

### [/memos] メモ一覧画面

- [ ] A-1
  - [Item--] タイトルにヒットする検索
  - [Steps-] タイトルに含まれる検索キーワードを入力し、[検索] 押下
  - [Result] ヒットしたメモのタイトルに、検索キーワードが含まれていること
- [ ] A-2
  - [Item--] 内容にヒットする検索
  - [Steps-] 内容に含まれる検索キーワードを入力し、[検索] 押下
  - [Result] ヒットしたメモの内容に、検索キーワードが含まれていること
- [ ] A-3
  - [Item--] タイトル or 内容どちらでもヒットする検索
  - [Steps-] タイトルか内容に含まれる検索キーワードを入力し、[検索] 押下
  - [Result]
    - ヒットしたメモのタイトルに、検索キーワードが含まれていること
    - ヒットしたメモの内容に、検索キーワードが含まれていること
- [ ] A-4
  - [Item--] キーワード未指定（q なし）
  - [Steps-] [/memos] にアクセス
  - [Result] 全件ヒットすること
- [ ] A-5
  - [Item--] キーワードが空（q=）
  - [Steps-] 検索キーワードを入力せず、[検索] 押下
  - [Result] 全件ヒットすること
- [ ] A-6
  - [Item--] ヒット件数が 0 件の場合
  - [Steps-] タイトルにも内容にも含まれない検索キーワードを入力し、[検索] 押下
  - [Result] メモが 1 件もない場合のレイアウトを表示
- [ ] A-7
  - [Item--] 並び順（最新順）の確認
  - [Steps-] タイトルか内容に含まれる検索キーワードを入力し、[検索] 押下
  - [Result] 並び順が最新順であること
- [ ] A-9
  - [Item--] 特殊文字を含む検索
  - [Steps-] 特殊文字を入力し、[検索] 押下
  - [Result] 特にエラーは発生しないこと

## ページネーション

### [/memos] メモ一覧画面

- [ ] A-1
  - [Item--] 1 ページ目の表示件数
  - [Steps-] [/memos] メモ一覧画面を開く
  - [Result] 1 ページ目に 1 ~ 10 件表示される
- [ ] A-2
  - [Item--] 2 ページ目の表示件数
  - [Steps-] [ページネーション-2] リンク押下
  - [Result] 2 ページ目に 11 ~ 20 件表示される
- [ ] A-3
  - [Item--] 3 ページ目の表示件数
  - [Steps-] [ページネーション-3] リンク押下
  - [Result] 3 ページ目に 21 ~ 30 件表示される
- [ ] A-4
  - [Item--] Prev の動作
  - [Steps-] [Prev] リンク押下
  - [Result] 前ページへ遷移すること
- [ ] A-5
  - [Item--] Next の動作
  - [Steps-] [Next] リンク押下
  - [Result] 次ページへ遷移すること
- [ ] A-6
  - [Item--] 並び順
  - [Steps-]
  - [Result] 新規作成降順であること

## [store] 登録処理

- [ ] A-1
  - [Item--] タイトル・内容を入力しないで [保存]
  - [Steps-] タイトル・内容を入力しないで [保存]を試みる
  - [Result] タイトルに HTML デフォルトのバリデーションが走ること
- [ ] A-2
  - [Item--] タイトルを入力しないで [保存]
  - [Steps-] タイトルを入力しないで [保存]を試みる
  - [Result] タイトルに HTML デフォルトのバリデーションが走ること
- [ ] A-3
  - [Item--] タイトルの最大文字数
  - [Steps-] タイトルに 100 文字以上入力しようと試みる
  - [Result] タイトルは 100 文字以上入力不可であること
- [ ] A-4
  - [Item--] 内容を入力しないで [保存]
  - [Steps-]
    1. タイトルを入力
    2. 内容を入力しない
    3. 保存
  - [Result]
    - [/memos] メモ一覧画面にリダイレクトされること
    - Flash 成功メッセージが表示がされること
    - 保存されたメモがメモ一覧の一番上に表示されること
- [ ] A-5
  - [Item--] タイトル・内容を入力して保存
  - [Steps-]
    1. タイトル・内容を入力
    2. 保存
  - [Result]
    - [/memos] メモ一覧画面にリダイレクトされること
    - Flash 成功メッセージが表示がされること
    - 保存されたメモがメモ一覧の一番上に表示されること

## [update] 更新処理

- [ ] A-1
  - [Item--] 既存の入力内容が反映されている
  - [Steps-] タイトル・内容が空ではないメモの [/memos/{memo}] メモ詳細画面を開く
  - [Result] 既存の入力内容が反映されていること
- [ ] A-2
  - [Item--] 任意項目が空である
  - [Steps-] 内容が空のメモの [/memos/{memo}] メモ詳細画面を開く
  - [Result] 内容が空である
- [ ] A-3
  - [Item--] タイトル・内容を入力しないで [保存]
  - [Steps-] タイトル・内容を入力しないで [保存]を試みる
  - [Result] タイトルに HTML デフォルトのバリデーションが走ること
- [ ] A-4
  - [Item--] タイトルを入力しないで [保存]
  - [Steps-] タイトルを入力しないで [保存]を試みる
  - [Result] タイトルに HTML デフォルトのバリデーションが走ること
- [ ] A-5
  - [Item--] タイトルの最大文字数
  - [Steps-] タイトルに 100 文字以上入力しようと試みる
  - [Result] タイトルは 100 文字以上入力不可であること
- [ ] A-6
  - [Item--] 内容を入力しないで [保存]
  - [Steps-]
    1. タイトルを入力
    2. 内容を入力しない
    3. 保存
  - [Result]
    - [/memos] メモ一覧画面にリダイレクトされること
    - Flash 成功メッセージが表示されること
    - 保存されたメモがメモ一覧の一番上に表示されること
- [ ] A-7
  - [Item--] タイトル・内容を入力して保存
  - [Steps-]
    1. タイトル・内容を入力
    2. 保存
  - [Result]
    - [/memos] メモ一覧画面にリダイレクトされること
    - Flash 成功メッセージが表示されること
    - 保存されたメモがメモ一覧の一番上に表示されること

## [destroy] 削除処理

- [ ] A-1
  - [Item--] キャンセル
  - [Steps-]
    1. [削除] リンクを押下
    2. 確認ダイアログ → キャンセル
  - [Result] 確認ダイアログを開く前と同じになること
- [ ] A-2
  - [Item--] 削除成功
  - [Steps-]
    1. [削除] リンクを押下
    2. 確認ダイアログ → OK
  - [Result]
    - [/memos] メモ一覧画面にリダイレクトされること
    - Flash 削除成功メッセージが表示されること
    - [/memos] メモ一覧画面から削除の確認が取れること

# 変更ファイルリスト

`git diff --name-only origin/main`

```
READEME.md
note/tutorial-memos.md
src/app/Http/Controllers/MemoController.php
src/app/Models/Memo.php
src/app/Providers/AppServiceProvider.php
src/app/Services/DebugService.php
src/database/migrations/2025_11_12_052231_create_memos_table.php
src/resources/views/layouts/memos.blade.php
src/resources/views/memos/_form.blade.php
src/resources/views/memos/create.blade.php
src/resources/views/memos/edit.blade.php
src/resources/views/memos/index.blade.php
src/resources/views/memos/show.blade.php
src/resources/views/welcome.blade.php
src/routes/web.php
```

# mysql

```bash
# コンテナに入る
docker compose exec mysql mysql -u root -p

root

show databases;

use app_db

show tables;

show columns from memos;
```

## memos テーブル

```sql
mysql> show columns from memos;
+------------+-----------------+------+-----+---------+----------------+
| Field      | Type            | Null | Key | Default | Extra          |
+------------+-----------------+------+-----+---------+----------------+
| id         | bigint unsigned | NO   | PRI | NULL    | auto_increment |
| title      | varchar(100)    | NO   |     | NULL    |                |
| content    | text            | YES  |     | NULL    |                |
| created_at | timestamp       | YES  |     | NULL    |                |
| updated_at | timestamp       | YES  |     | NULL    |                |
+------------+-----------------+------+-----+---------+----------------+

mysql> show create table memos;
+-------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Table | Create Table
                                                                                      |
+-------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| memos | CREATE TABLE `memos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci |
+-------+-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
1 row in set (0.00 sec)
```

# 作成手順

## 流れ

- [x] モデル & リソースコントローラー & マイグレーション
- [x] マイグレーション編集
- [x] マイグレーション実行
- [x] モデル設定
- [x] ルーティング
- [x] コントローラー
- ビュー作成
  - [x] ベースレイアウト
  - [x] 共通フォーム
  - [x] 一覧画面
  - [x] 新規作成画面
  - [x] 編集画面
  - [x] 詳細画面

## モデル & リソースコントローラー & マイグレーション

```bash
docker compose exec app php src/artisan make:model Memo -mcr
# -m  : migration作成
# -c  : controller作成
# -r  : resourceスタイルのアクション一式
```

## マイグレーション実行

```bash
docker compose exec app php src/artisan migrate
```

# 勉強

## 命名について

## モデルは単数形

- モデル : 単数形（1 件を表す）
- テーブル : 複数形（複数のレコードを持つ）

だから

- Model → Memo
- Table → memos

が自然

## Controller も単数形

```
モデル名 + Controller
```

が基本だから、

- Model : Memo
- Controller : MemoController
- Resources Routes : /memos
- Route Model Binding : {memo}

のセットで噛み合う

## Cake との違い

## 1 件のレコードの扱い方

| 概念           | CakePHP        | Laravel                        |
| -------------- | -------------- | ------------------------------ |
| 1 レコード     | **Entity**     | **Model（Eloquent Instance）** |
| クエリ実行担当 | **Table**      | **Model（Eloquent Builder）**  |
| バリデーション | Table          | FormRequest / Model            |
| 保存           | Table → Entity | Model の save()                |

CakePHP → 「データは Entity、操作は Table でやる。
Laravel → 「モデルが全部管理する」

みたいなイメージ。

## 処理の名前

これらは決まってるらしい

- 一覧 : index
- 作成フォーム : create
- 登録 : store
- 詳細表示 : show
- 編集フォーム : edit
- 更新 : update
- 削除 : destroy

## メソッドの分け方

- 画面表示と処理で、メソッドは分けたほうがいい

## REST とは？

# debug

- app/Services/DebugService.php 作成

# ページネーション

- src/app/Providers/AppServiceProvider.php
  - ブーストラップ用のページネーションに変更。デフォルトではテイルウィンドウらしい。

# scss

- `npm install -D sass`
- [resources/scss/memo/style.scss] 作成
- blade で読み込む
  - `@vite('resources/scss/memo/style.scss')`
- ビルド
  - `npm run dev`
- 本番では
  - `npm run build`

# バリデーション

- FormRequest で管理する
- docker compose exec app bash
- php artisan make:request MemoRequest
- src/app/Http/Requests/MemoRequest.php を書く
- コントローラーで呼び出し
