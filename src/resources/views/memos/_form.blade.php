@csrf
<div class="mb-3">
  <label class="form-label">タイトル</label>
  <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
    value="{{ old('title', $memo->title ?? '') }}" required maxlength="100">
  @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
  <label class="form-label">内容</label>
  <textarea name="content" rows="6" class="form-control @error('content') is-invalid @enderror">{{ old('content', $memo->content ?? '') }}</textarea>
  @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>
<button class="btn btn-primary">保存</button>
<a href="{{ route('memos.index') }}" class="btn btn-secondary">戻る</a>