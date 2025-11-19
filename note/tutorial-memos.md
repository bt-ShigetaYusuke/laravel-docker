# 変更ファイルリスト

- `src/database/migrations/2025_11_12_052231_create_memos_table.php`
- `src/app/Models/Memo.php`
- `src/app/Http/Controllers/MemoController.php`
- `src/resources/views/layouts/memo.blade.php`
- `src/resources/views/memos/_form.blade.php`
- `src/resources/views/memos/index.blade.php`

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
- [ ] コントローラー
- ビュー作成
  - [x] ベースレイアウト
  - [x] 共通フォーム
  - [x] 一覧画面
  - [x] 新規作成画面
  - [ ] 編集画面
  - [ ] 詳細画面

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

## コントローラ実装

```php
// app/Http/Controllers/MemoController.php

public function update(Request $request, \App\Models\Memo $memo)
{
    $validated = $request->validate([
        'title'   => ['required','string','max:100'],
        'content' => ['nullable','string'],
    ]);

    $memo->update($validated);
    return redirect()->route('memos.index')->with('success', '更新したよ');
}

public function destroy(\App\Models\Memo $memo)
{
    $memo->delete();
    return redirect()->route('memos.index')->with('success', '削除したよ');
}
```

## ビュー作成

## ビルドキャッシュ

```
# ルーティング追加後にキャッシュ系使ってる場合はクリア
docker compose exec app php src/artisan route:clear
docker compose exec app php src/artisan config:clear
docker compose exec app php src/artisan view:clear
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
