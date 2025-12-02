@extends('layouts.okozukai')
@section('title', 'okozukai-balance')
@section('page-title', '残高')
@section('page-content-class', 'balance')

@section('content')

  @if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
  @endif

  @if (session('error'))
    <p style="color: red;">{{ session('error') }}</p>
  @endif

  {{--
  <p>今月の予算：{{ number_format($budget) }} 円</p>
  --}}

  <div class="balance">
    <h2 class="balance__month">{{ now()->format('n') }}月</h2>
    <ul class="balance__list">
      <li class="balance__item">
        <span class="balance__label">支出合計：</span>
        <span class="balance__value">{{ number_format($totalSpentThisMonth) }}&nbsp;円</span>
      </li>
      <li class="balance__item">
        <span class="balance__label">残り金額：</span>
        <span class="balance__value">{{ number_format($remaining) }}&nbsp;円</span>
      </li>
    </ul>
  </div>

  <hr>

  <div class="saving">
    <ul class="saving__list">
      <li class="saving__item">
        <span class="saving__label">貯金額：</span>
        <span class="saving__value">{{ number_format($totalSaving) }}&nbsp;円</span>
      </li>
    </ul>
  </div>
@endsection
