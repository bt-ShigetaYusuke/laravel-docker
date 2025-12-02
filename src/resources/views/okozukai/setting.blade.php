@extends('layouts.okozukai')
@section('title', 'okozukai-setting')
@section('page-title', '設定')

@section('content')

  @if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
  @endif

  @if (session('error'))
    <p style="color: red;">{{ session('error') }}</p>
  @endif

  <div>
    <h2>月初ボタン</h2>
    <p>※ {{ $closeTargetLabel }} を締めて、貯金額を更新します。</p>

    @if ($alreadyClosed)
      <p>この月はすでに月次サマリ作成済みです。</p>
    @else
      <form method="POST" action="{{ route('okozukai.balance.monthly_close') }}"
        onsubmit="return confirm('{{ $closeTargetLabel }} の月次サマリを作成します。よろしいですか？');">
        @csrf
        <button type="submit">月初ボタンを押す</button>
      </form>
    @endif
  </div>
@endsection
