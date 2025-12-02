@extends('layouts.okozukai')
@section('title', 'okozukai-history')
@section('page-title', '履歴')
@section('page-content-class', 'history')

@php
  $runningTotal = 0;
@endphp

@section('content')
  @if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
  @endif

  <form method="GET" action="{{ route('okozukai.history') }}" class="form-search">
    <ul class="form-search__list">

      <li class="form-search__item">
        <select name="year_month">
          @foreach ($months as $m)
            <option value="{{ $m['value'] }}" @selected($yearMonth === $m['value'])>
              {{ $m['label'] }}
            </option>
          @endforeach
        </select>
      </li>

      <li class="form-search__item">
        <select name="category_id">
          <option value="">カテゴリ</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}" @selected($categoryId == $category->id)>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </li>

    </ul>

    <button type="submit" class="form-search__btn">検索</button>
  </form>

  <hr>

  <table class="history-table">
    <thead class="history-table__head">
      <tr class="history-table__row">
        <th class="history-table__cell">日付</th>
        <th class="history-table__cell">金額</th>
        <th class="history-table__cell">当月累計</th>
        <th class="history-table__cell">カテゴリ</th>
        <th class="history-table__cell">操作</th>
      </tr>
    </thead>
    <tbody class="history-table__body">
      @forelse ($expenses as $expense)
        @php
          // この行の金額を累計に加算
          $runningTotal += $expense->amount;
        @endphp
        <tr class="history-table__row">
          <td class="history-table__cell history-table__cell--spent_at">{{ $expense->spent_at->format('d日') }}</td>
          <td class="history-table__cell history-table__cell--amount">{{ number_format($expense->amount) }} 円</td>
          <td class="history-table__cell history-table__cell--runningTotal">{{ number_format($runningTotal) }} 円</td>
          <td class="history-table__cell">{{ optional($expense->category)->name ?? 'なし' }}</td>
          <td class="history-table__cell history-table__cell--destroy">
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
          <td colspan="5">この条件の履歴はありません。</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  {{ $expenses->links() }}
@endsection
