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

- モデル & リソースコントローラー & マイグレーション
- マイグレーション編集
- マイグレーション実行
- モデル設定
- ルーティング
- 一覧
  - コントローラ実装
  - ビュー作成
    - ベースレイアウト
    - 共通フォーム
    - 一覧
  - 表示確認
- 新規作成
  - コントローラ実装
  - ビュー作成
    - 新規作成
- 編集
  - コントローラ実装
  - ビュー作成
    - 編集
- 詳細
  - コントローラ実装
  - ビュー作成
    - 詳細

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

public function show(\App\Models\Memo $memo)
{
    return view('memos.show', compact('memo'));
}

public function edit(\App\Models\Memo $memo)
{
    return view('memos.edit', compact('memo'));
}

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

### 編集

```php
// resources/views/memos/edit.blade.php
@extends('layouts.app')
@section('title','メモ編集')
@section('content')
<h1 class="h3 mb-3">メモ編集</h1>
<form method="POST" action="{{ route('memos.update', $memo) }}">
  @method('PUT')
  @include('memos._form', ['memo' => $memo])
</form>
@endsection
```

### 詳細

```php
// resources/views/memos/show.blade.php
@extends('layouts.app')
@section('title',$memo->title)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 m-0">{{ $memo->title }}</h1>
  <div class="d-flex gap-2">
    <a class="btn btn-sm btn-primary" href="{{ route('memos.edit', $memo) }}">編集</a>
    <form method="POST" action="{{ route('memos.destroy', $memo) }}"
          onsubmit="return confirm('削除する？')">
      @csrf
      @method('DELETE')
      <button class="btn btn-sm btn-danger">削除</button>
    </form>
  </div>
</div>
<pre class="p-3 bg-white border rounded" style="white-space: pre-wrap;">{{ $memo->content }}</pre>
<a href="{{ route('memos.index') }}" class="btn btn-secondary mt-3">一覧へ</a>
@endsection
```

## ビルドキャッシュ

```
# ルーティング追加後にキャッシュ系使ってる場合はクリア
docker compose exec app php src/artisan route:clear
docker compose exec app php src/artisan config:clear
docker compose exec app php src/artisan view:clear
```
