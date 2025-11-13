<?php

namespace App\Http\Controllers;

use App\Models\Memo;
use Illuminate\Http\Request;

class MemoController extends Controller
{
    /**
     * メモ一覧画面
     */
    public function index()
    {
        /**
         * URLパラメータ（検索キーワード）を取得する
         * 
         * /memos?q=仕事 みたいにアクセスすると、
         * $q に "仕事" が入る
         */
        $q = request('q');

        /**
         * 一覧データを取得
         * 
         * when() : 条件文みたいなやつ
         * 
         * ->latest() : 並び順を新しい順にする
         * 
         * ->pagenaet() : ページネーション
         * 
         * ->withQueryString() :
         *   ページネーションしても検索条件（?q=仕事）を保持してくれるやつ
         *   検索条件を保持したままページ送りできる
         * 
         * fn($query) : PHPのアロー関数
         *   Laravelではこの書き方が主流らしい
         */
        $memos = \App\Models\Memo::when(
            $q,
            fn($query) =>
            $query->where('title', 'like', "%$q%")
                ->orWhere('content', 'like', "%$q%")
        )->latest()->paginate(10)->withQueryString();

        /**
         * ビューの表示 & 変数渡し
         * 
         * resources/views/memos/index.blade.php
         * 
         * 'memos' => $memos, 'q' => $q
         */
        return view('memos.index', compact('memos', 'q'));
    }

    /**
     * 新規作成画面
     */
    public function create()
    {
        return view('memos.create');
    }

    /**
     * 新しいメモをDBに保存する処理
     * 
     * Request $request :
     *   フォームから送られてきたHTTPリクエストをLaravelが自動で渡してくれる
     */
    public function store(Request $request)
    {
        /**
         * バリデーション
         * 
         * エラーがあると自動で前のページへリダイレクトして
         * エラーメッセージをセッションに保存する
         * 
         * OKならバリデーション済みデータだけを $validated に返す
         */
        $validated = $request->validate([
            'title'   => ['required', 'string', 'max:100'],
            'content' => ['nullable', 'string'],
        ]);

        /**
         * DBに保存
         * 
         * Memoモデルの create() メソッドで、
         * $validated のデータをそのままDBの memos テーブルに INSERT する
         * 
         * create() は「Laravelが用意したEloquentの標準メソッド」で、
         * 「新しいレコードをサクッと作成するための便利ショートカット」
         */
        \App\Models\Memo::create($validated);

        /**
         * /memos へリダイレクトする
         * 
         * セッションに一時的なメッセージを保存し、
         * リダイレクト先ページでこれを使ってアラートを表示できる
         */
        return redirect()->route('memos.index')->with('success', '作成したよ');
    }

    /**
     * 詳細画面
     * 
     * Memo $memo でモデルが自動的に読み込まれる
     * → ルートモデルバイディングってやつ
     * 
     * ルートモデルバイディング :
     *   パラメータ名＝モデル名（小文字）
     *     Model: Memo
     *     パラメータ名: {memo}
     *     Controller: show(Memo $memo)
     */
    public function show(Memo $memo)
    {
        return view('memos.show', compact('memo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Memo $memo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Memo $memo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Memo $memo)
    {
        //
    }
}
