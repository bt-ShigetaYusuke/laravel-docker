<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OkozukaiExpense;
use App\Models\OkozukaiCategory;
use Carbon\Carbon;

class OkozukaiController extends Controller
{
    /**
     * 支出入力画面
     */
    public function index()
    {
        /**
         * 今月の支出合計
         * 
         * 【Carbon でできること】
         * - 今日の日付欲しい
         * - 今月の1日目欲しい
         * - 来週の日付欲しい
         * - 日付を + 3日 したい
         * - フォーマットを変えたい
         */
        $monthlyTotal = OkozukaiExpense::monthlyTotal(Carbon::today());

        /**
         * カテゴリ全部
         * 
         * カテゴリ全部を取得
         */
        $categories = OkozukaiCategory::all();

        return view('okozukai.index', compact(
            'monthlyTotal',
            'categories',
        ));
    }

    /**
     * 登録
     * 
     * 支出登録処理
     */
    public function store(Request $request)
    {
        $request->validate([
            // 'spent_at' => ['required', 'date'],

            // 必須 & 数値 1 以上
            'amount' => ['required', 'integer', 'min:1'],

            // 任意, カテゴリIDが存在しない場合はエラー
            'okozukai_category_id' => ['nullable', 'exists:okozukai_categories,id'],
        ]);

        OkozukaiExpense::create([
            'spent_at' => $request->spent_at,
            'amount' => $request->amount,
            'okozukai_category_id' => $request->okozukai_category_id,
        ]);

        return redirect()->route('okozukai.index')->with('success', 'success!');
    }
}
