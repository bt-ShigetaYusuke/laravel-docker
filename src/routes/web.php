<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MemoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OkozukaiController;
use App\Http\Controllers\OkozukaiBalanceController;
use App\Http\Controllers\OkozukaiHistoryController;

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
 * 
 * ルートモデルバインディング
 * → GET /memos/{memo} → memos.show
 * ここで {memo} って名前が Memo $memo と一致している
 */
Route::resource('memos', MemoController::class);

Route::prefix('okozukai')->name('okozukai.')->group(function () {
    Route::get('/', [OkozukaiController::class, 'index'])->name('index');
    Route::post('/spend', [OkozukaiController::class, 'store'])->name('spend');

    Route::get('/balance', [OkozukaiBalanceController::class, 'index'])->name('balance');

    // ⭐ 月初ボタン押下時の処理
    Route::post('/balance/monthly-close', [OkozukaiBalanceController::class, 'monthlyClose'])
        ->name('balance.monthly_close');

    Route::get('/history', [OkozukaiHistoryController::class, 'index'])->name('history');
    Route::delete('/history/{expense}', [OkozukaiHistoryController::class, 'destroy'])->name('history.destroy');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::middleware('auth')->prefix('okozukai')->name('okozukai.')->group(function () {
    Route::get('/', [OkozukaiController::class, 'index'])->name('index');
    Route::post('/spend', [OkozukaiController::class, 'store'])->name('spend');

    Route::get('/balance', [OkozukaiBalanceController::class, 'index'])->name('balance');

    Route::get('/history', [OkozukaiHistoryController::class, 'index'])->name('history');
    Route::delete('/history/{expense}', [OkozukaiHistoryController::class, 'destroy'])->name('history.destroy');

    Route::post('/balance/monthly-close', [OkozukaiBalanceController::class, 'monthlyClose'])
        ->name('balance.monthly_close');
});

require __DIR__ . '/auth.php';
