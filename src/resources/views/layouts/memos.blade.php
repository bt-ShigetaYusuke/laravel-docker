{{--

Laravelの「レイアウトテンプレート」

「このHTMLを全ページの共通テンプレートにして、
各ページごとに @section('content') で中身を差し込む」
という仕組み

--}}

<!doctype html>
<html lang="ja">

<head>
  <meta charset="utf-8">

  {{--
    子ビュー（個別ページ）側で @section('title', 'メモ一覧') と書くと、ここにその文字が入る
    もし指定がない場合、'App Name' がデフォルトで入る
  --}}
  <title>@yield('title', 'App Name')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">

      {{--
        Laravel のルートヘルパー
        
        memos.index のルートのURLを自動生成してくれる
        出力結果 :
          href="http://localhost:1025/memos"
          href="http://localhost:1025/memos/create"
      --}}
      <a class="navbar-brand" href="{{ route('memos.index') }}">Memo</a>
      <a class="btn btn-sm btn-primary" href="{{ route('memos.create') }}">新規作成</a>
    </div>
  </nav>

  <div class="container">

    {{--
      セッションに 'success' メッセージが入ってるとき表示する要素
    --}}
    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{--
      ここに各ページ固有の内容が差し込まれる

      子ビューでこんな感じで書く :
        @extends('layouts.memos')

        @section('content')
        各ページ固有の内容
        @endsection
    --}}
    @yield('content')
  </div>
</body>

</html>
