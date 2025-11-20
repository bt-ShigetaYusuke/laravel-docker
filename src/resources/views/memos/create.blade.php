@extends('layouts.memos')
@section('title', '新規メモ')
@section('content')
  <h1 class="h3 mb-3">新規メモ</h1>
  <form method="POST" action="{{ route('memos.store') }}">
    @include('memos._form')
  </form>
@endsection
