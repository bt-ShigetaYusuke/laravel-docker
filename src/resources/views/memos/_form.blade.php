{{--
  @csrf :
    Laravelの CSRF（クロスサイトリクエストフォージェリ）対策トークン を埋め込むディレクティブ。
    フォーム送信時に、Laravelが自動でこのトークンを検証して「外部からの不正POST送信」を防ぐ。
    ↓ 実際はHTMLの中でこんな感じに変換されてる
    <input type="hidden" name="_token" value="ランダムな長いトークン">
    
    フォームの <form> タグの中には必ず @csrf を入れる
--}}
@csrf

{{--
  タイトル入力欄
  
  value="{{ old('title', $memo->title ?? '')}}" :
    → 入力値の保持と初期値の両対応
      old('title') : 前回フォーム送信でエラーになった時の入力値
      $memo->title ?? '' :
        編集画面なら既存メモのタイトルを表示
        新規作成なら空文字
  
  @error('title') ... @enderror :
    → その項目のエラーメッセージを表示するBladeディレクティブ
--}}
<div class="mb-3">
  <label class="form-label">タイトル</label>

  <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
    value="{{ old('title', $memo->title ?? '') }}" required maxlength="100">
  @error('title')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>

{{--
  コンテンツ入力欄
--}}
<div class="mb-3">
  <label class="form-label">内容</label>
  <textarea name="content" rows="6" class="form-control @error('content') is-invalid @enderror">{{ old('content', $memo->content ?? '') }}</textarea>
  @error('content')
    <div class="invalid-feedback">{{ $message }}</div>
  @enderror
</div>
<button class="btn btn-primary">保存</button>
<a href="{{ route('memos.index') }}" class="btn btn-secondary">戻る</a>
