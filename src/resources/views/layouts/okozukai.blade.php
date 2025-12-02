<!doctype html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>@yield('title', 'okozukai')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/reset.css', 'resources/scss/okozukai/style.scss'])
</head>

<body class="body">
  <header class="header">
    <div class="header__inner">
      <a href="{{ route('okozukai.index') }}" class="header__logo">okozukai</a>
    </div>
  </header>
  <h1 class="page-title">@yield('page-title', 'Page Title')</h1>
  <div class="page-content page-content--@yield('page-content-class')">
    @yield('content')
  </div>
  <footer class="footer">
    <div class="footer__inner">
      <nav class="footer__nav">
        <ul class="footer__navList">
          <li class="footer__navItem"><a href="{{ route('okozukai.index') }}" class="footer__navLink">T</a></li>
          <li class="footer__navItem"><a href="{{ route('okozukai.balance') }}" class="footer__navLink">B</a></li>
          <li class="footer__navItem"><a href="{{ route('okozukai.history') }}" class="footer__navLink">H</a></li>
          <li class="footer__navItem"><a href="{{ route('okozukai.setting') }}" class="footer__navLink">S</a></li>
        </ul>
      </nav>
    </div>
  </footer>
</body>

</html>
{{--
<form method="POST" action="{{ route('logout') }}">
  @csrf
  <button type="submit">Logout</button>
</form>
--}}
