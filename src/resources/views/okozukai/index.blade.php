@extends('layouts.okozukai')
@section('title', 'okozukai-top')

@section('content')
  <h1>今月の支出合計：{{ number_format($monthlyTotal) }}円</h1>

  <form action="{{ route('okozukai.spend') }}" method="POST">
    @csrf
    <input type="date" name="spent_at" value="{{ now()->toDateString() }}">
    <input type="number" name="amount" placeholder="金額">

    <select name="okozukai_category_id">
      <option value="">カテゴリなし</option>
      @foreach ($categories as $category)
        <option value="{{ $category->id }}">{{ $category->name }}</option>
      @endforeach
    </select>

    <button type="submit">保存</button>
  </form>
@endsection
