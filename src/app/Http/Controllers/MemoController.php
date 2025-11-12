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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Memo $memo)
    {
        //
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
