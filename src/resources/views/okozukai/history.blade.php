@extends('layouts.okozukai')
@section('title', 'okozukai-history')

@php
  $runningTotal = 0;
@endphp

@section('content')
  <h1>収支履歴</h1>

  @if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
  @endif

  {{-- 検索フォーム（年月 ＋ カテゴリ） --}}
  <form method="GET" action="{{ route('okozukai.history') }}">
    <div>
      <label>年月</label>
      <select name="year_month">
        @foreach ($months as $m)
          <option value="{{ $m['value'] }}" @selected($yearMonth === $m['value'])>
            {{ $m['label'] }}
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label>カテゴリ</label>
      <select name="category_id">
        <option value="">すべて</option>
        @foreach ($categories as $category)
          <option value="{{ $category->id }}" @selected($categoryId == $category->id)>
            {{ $category->name }}
          </option>
        @endforeach
      </select>
    </div>

    <button type="submit">検索</button>
  </form>

  <hr>

  <table border="1" cellpadding="4">
    <thead>
      <tr>
        <th>日付</th>
        <th>金額</th>
        <th>当月累計</th>
        <th>カテゴリ</th>
        <th>操作</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($expenses as $expense)
        @php
          // この行の金額を累計に加算
          $runningTotal += $expense->amount;
        @endphp
        <tr>
          <td>{{ $expense->spent_at }}</td>
          <td>{{ number_format($expense->amount) }} 円</td>
          <td>{{ number_format($runningTotal) }} 円</td>
          <td>{{ optional($expense->category)->name ?? 'なし' }}</td>
          <td>
            <form action="{{ route('okozukai.history.destroy', $expense) }}" method="POST"
              onsubmit="return confirm('この支出を取消しますか？');">
              @csrf
              @method('DELETE')
              <button type="submit">取消</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="4">この条件の履歴はありません。</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{ $expenses->links() }}
@endsection
