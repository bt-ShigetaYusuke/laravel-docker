<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>残高画面</title>
</head>

<body>
  {{-- resources/views/okozukai/balance.blade.php --}}

  <h1>残高画面</h1>

  @if (session('success'))
    <p style="color: green;">{{ session('success') }}</p>
  @endif

  @if (session('error'))
    <p style="color: red;">{{ session('error') }}</p>
  @endif

  <div>
    <p>今月の予算：{{ number_format($budget) }} 円</p>
    <p>今月の支出合計：{{ number_format($totalSpentThisMonth) }} 円</p>
    <p>今月の残り金額：{{ number_format($remaining) }} 円</p>
  </div>

  <hr>

  <div>
    <p>現在の貯金額（累計）：{{ number_format($totalSaving) }} 円</p>
  </div>

  <hr>

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

  <hr>

  <a href="{{ route('okozukai.index') }}">トップに戻る</a>
  <a href="{{ route('okozukai.history') }}">履歴を見る</a>


</body>

</html>
