@extends('layouts.memos')
@section('title', 'メモ編集')
@section('content')
  <h1 class="h3 mb-3">メモ編集</h1>
  <form method="POST" action="{{ route('memos.update', $memo) }}">
    @method('PUT')
    @include('memos._form', ['memo' => $memo])
  </form>
@endsection
