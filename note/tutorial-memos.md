# 作成手順

## モデル & リソースコントローラー & マイグレーション

```bash
docker compose exec app php src/artisan make:model Memo -mcr
# -m  : migration作成
# -c  : controller作成
# -r  : resourceスタイルのアクション一式
```

### マイグレーション編集

```php
// database/migrations/xxxx_xx_xx_create_memos_table.php
public function up(): void
{
    Schema::create('memos', function (Blueprint $table) {
        $table->id();
        $table->string('title', 100);
        $table->text('content')->nullable();
        $table->timestamps();
    });
}
```

### マイグレーション実行

```bash
docker compose exec app php src/artisan migrate
```

## モデル設定

```php
// app/Models/Memo.php
class Memo extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content'];
}
```

## ルーティング

```php
// routes/web.php

use App\Http\Controllers\MemoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return redirect()->route('memos.index'); });
Route::resource('memos', MemoController::class);
```

## コントローラ実装

```php
// app/Http/Controllers/MemoController.php

public function index()
{
    $q = request('q');
    $memos = \App\Models\Memo::when($q, fn($query) =>
        $query->where('title', 'like', "%$q%")
              ->orWhere('content', 'like', "%$q%")
    )->latest()->paginate(10)->withQueryString();

    return view('memos.index', compact('memos', 'q'));
}

public function create()
{
    return view('memos.create');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'title'   => ['required','string','max:100'],
        'content' => ['nullable','string'],
    ]);

    \App\Models\Memo::create($validated);
    return redirect()->route('memos.index')->with('success', '作成したよ');
}

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

### ベースレイアウト

```php
// resources/views/layouts/app.blade.php
<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>@yield('title','Memo App')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="{{ route('memos.index') }}">Memo</a>
    <a class="btn btn-sm btn-primary" href="{{ route('memos.create') }}">新規作成</a>
  </div>
</nav>

<div class="container">
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @yield('content')
</div>
</body>
</html>
```

### 共通フォーム

```php
// resources/views/memos/_form.blade.php
@csrf
<div class="mb-3">
  <label class="form-label">タイトル</label>
  <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
         value="{{ old('title', $memo->title ?? '') }}" required maxlength="100">
  @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
  <label class="form-label">内容</label>
  <textarea name="content" rows="6" class="form-control @error('content') is-invalid @enderror">{{ old('content', $memo->content ?? '') }}</textarea>
  @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<button class="btn btn-primary">保存</button>
<a href="{{ route('memos.index') }}" class="btn btn-secondary">戻る</a>
```

### 一覧

```php
// resources/views/memos/index.blade.php
@extends('layouts.app')
@section('title','メモ一覧')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 m-0">メモ一覧</h1>
  <form method="GET" class="d-flex gap-2">
    <input type="search" name="q" class="form-control" placeholder="検索…" value="{{ $q }}">
    <button class="btn btn-outline-secondary">検索</button>
  </form>
</div>

@if($memos->count())
  <div class="list-group mb-3">
    @foreach($memos as $memo)
      <a class="list-group-item list-group-item-action" href="{{ route('memos.show',$memo) }}">
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1">{{ $memo->title }}</h5>
          <small class="text-muted">{{ $memo->updated_at->diffForHumans() }}</small>
        </div>
        <p class="mb-1 text-muted">{{ Str::limit($memo->content, 120) }}</p>
      </a>
    @endforeach
  </div>
  {{ $memos->links() }}
@else
  <p class="text-muted">まだメモがないよ。右上から作成してみて！</p>
@endif
@endsection
```

### 新規作成

```php
// resources/views/memos/create.blade.php
@extends('layouts.app')
@section('title','新規メモ')
@section('content')
<h1 class="h3 mb-3">新規メモ</h1>
<form method="POST" action="{{ route('memos.store') }}">
  @include('memos._form')
</form>
@endsection
```

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
