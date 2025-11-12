<?php

use App\Http\Controllers\MemoController;
use Illuminate\Support\Facades\Route;

/**
 * トップページ
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * /memos
 * 
 * Route::resource() : Laravelが「決まった7種類のURL」だけ自動生成する仕組み
 * index, create, store, show, edit, update, destroy
 * 
 * その他のアクションを追加したら以下のようにルートも追加する
 * 
 * Route::get('memos/search', [MemoController::class, 'search'])->name('memos.search');
 * → /memos/search にアクセスしたら → search()が実行されるって感じ
 */
Route::resource('memos', MemoController::class);