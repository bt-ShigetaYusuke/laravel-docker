{{--
  @extends() :
    このビューは resources/views/layouts/memos.blade.php を親テンプレートとして使うという宣言
  
  @section() :
    レイアウト側で @yield() と書かれていたところに埋め込まれる
--}}
@extends('layouts.memos')
@section('title','メモ一覧')

{{--
  ここからが「このページ固有の中身」
  @yield('content') に埋め込まれる
--}}
@section('content')

{{--
  
--}}

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 m-0">メモ一覧</h1>

  {{--
    検索フォーム
    
    入力した文字が ?q=〇〇 の形でURLについて送信される

    value="{{ $q }}" :
  直前の検索キーワードを保持
  --}}
  <form method="GET" class="d-flex gap-2">
    <input type="search" name="q" class="form-control" placeholder="検索…" value="{{ $q }}">
    <button class="btn btn-outline-secondary">検索</button>
  </form>
</div>

{{--
  $memos が空でなければ（=1件以上あれば）一覧を表示
--}}
@if($memos->count())
<div class="list-group mb-3">

  {{--
    $memos（MemoController@indexで渡されたページネーション付きのコレクション）をループ
  --}}
  @foreach($memos as $memo)

  {{--
    /memos/{id} のURLを自動生成
  --}}
  <a class="list-group-item list-group-item-action" href="{{ route('memos.show',$memo) }}">
    <div class="d-flex w-100 justify-content-between">

      {{--
        メモのタイトル
      --}}
      <h5 class="mb-1">{{ $memo->title }}</h5>

      {{--
        メモの更新日時
        
        diffForHumans() :
          人間向け表現にしてくれるやつ
      --}}
      <small class="text-muted">{{ $memo->updated_at->diffForHumans() }}</small>
    </div>

    {{--
      メモのコンテンツ

      Str::limit() :
        文字数を制限して、末尾に ... をつける
    --}}
    <p class="mb-1 text-muted">{{ Str::limit($memo->content, 120) }}</p>
  </a>
  @endforeach
</div>

{{--
  ページネーション
  
  ページネーションを自動生成してくれてる
--}}
{{ $memos->links() }}

{{--
  $memos が空
--}}
@else
<p class="text-muted">まだメモがないよ。右上から作成してみて！</p>
@endif
@endsection