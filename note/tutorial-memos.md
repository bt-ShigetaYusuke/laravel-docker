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

# 変更ファイルリスト

`git diff --name-only origin/main`

```
READEME.md
note/tutorial-memos.md
src/app/Http/Controllers/MemoController.php
src/app/Models/Memo.php
src/database/migrations/2025_11_12_052231_create_memos_table.php
src/resources/views/layouts/memos.blade.php
src/resources/views/memos/_form.blade.php
src/resources/views/memos/create.blade.php
src/resources/views/memos/edit.blade.php
src/resources/views/memos/index.blade.php
src/resources/views/memos/show.blade.php
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

# 命名について

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

# Cake との違い

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

# 処理の名前

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

# REST とは？

# debug

- app/Services/DebugService.php 作成
