@extends('layouts.memos')
@section('title', 'メモ編集')
@section('content')
  <h1 class="h3 mb-3">メモ編集</h1>
  <form method="POST" action="{{ route('memos.update', $memo) }}">
    {{--
    メソッドを PUT に変更

    HTML の method 属性では、 GET か POST しか選択できないらしい

    PUT = 更新（上書き）用の HTTP メソッド

    どのリクエストを使うかは、 REST のルールってのがあるらしい
    --}}
    @method('PUT')
    @include('memos._form', ['memo' => $memo])
  </form>
@endsection
