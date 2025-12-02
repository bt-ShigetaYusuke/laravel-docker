@extends('layouts.okozukai')
@section('title', 'okozukai-top')
@section('page-title', 'TOP')
@section('page-content-class', 'top')

@section('content')
  <p class="page-text page-text--top">今月の支出合計：{{ number_format($monthlyTotal) }}円</p>

  <form id="spend" class="form-spend" action="{{ route('okozukai.spend') }}" method="POST">
    @csrf

    <label for="amount" class="form-spend__label">
      <input id="amount" type="number" name="amount" placeholder="¥"
        class="form-spend__input form-spend__input--amount">
    </label>

    {{--
    <label for="spent-at" class="form-spend__label">
      <input id="spent-at" type="date" name="spent_at" value="{{ now()->toDateString() }}" class="form-spend__input">
    </label>
    --}}

    {{--
    <label for="category-id" class="form-spend__label">
      <select id="category-id" name="okozukai_category_id" class="form-spend__input">
        <option value="">カテゴリなし</option>
        @foreach ($categories as $category)
          <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
      </select>
    </label>
    --}}

    <button type="submit" class="form-spend__btn">保存</button>
  </form>
@endsection
