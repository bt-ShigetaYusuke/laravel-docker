@extends('layouts.memos')
@section('title',$memo->title)
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h3 m-0">{{ $memo->title }}</h1>
  <div class="d-flex gap-2">

    {{--
      memo.edit へ飛ぶリンク

      $memo で IDを自動で埋めてくれる
    --}}
    <a class="btn btn-sm btn-primary" href="{{ route('memos.edit', $memo) }}">編集</a>

    {{--
      削除ボタン（フォーム）

      method="POST" だけど、@method('DELETE') で擬似的に DELETE リクエストへ変換
    --}}
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