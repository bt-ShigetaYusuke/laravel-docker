<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OkozukaiExpense extends Model
{
    protected static function booted()
    {
        /**
         * OkozukaiExpense::create() したら発火
         */
        static::creating(function ($expense) {
            // spent_at（使った日付）が空なら、今日の日付を勝手に入れる
            if (empty($expense->spent_at)) {
                $expense->spent_at = now()->toDateString();
            }
        });
    }

    /**
     * キャスト＝データの型を変換すること
     * プログラミングでは「型変換（type casting）」のことを キャスト って呼ぶのが普通らしい
     * DB から値を取ってくると、たいてい 全部“文字列” なんだけど、
     * そのままだと使いづらいことが多い。
     */
    protected $casts = [
        /**
         * 日付を、data型に変換
         * 以下みたいなことができるようになる
         * $expense->spent_at->format('m月d日');
         */
        'spent_at' => 'date',
    ];

    /**
     * 一括代入していいやつ
     * 
     * この3つ以外はcreate()とかupdate()で勝手に書き換えちゃダメ
     */
    protected $fillable = [
        'spent_at',
        'amount',
        'okozukai_category_id',
    ];

    /**
     * belongsTo（〜に属している）
     * 
     * OkozukaiExpense（支出）
     * → カテゴリに属している（belongsTo）
     * OkozukaiCategory（カテゴリ）
     * → 複数の支出を持つことが多い（hasMany）
     * 
     * $expense->category でカテゴリを取れる
     * 
     * 1対多の関係
     * 
     * 1つのカテゴリ → たくさんの支出
     * 親（hasMany） : カテゴリ
     * 子（belongsTo） : 支出
     * 
     * こんな感じで表現する関係を、1対多とかっていう。
     */
    public function category()
    {
        return $this->belongsTo(OkozukaiCategory::class, 'okozukai_category_id');
    }

    /**
     * 当月の支出合計を取得する
     * 
     * 【スコープメソッド】
     * scopeXXXX と名づけるって決まってる
     * 
     * スコープ = Model に“クエリの定型パターン”を追加する機能
     * 呼び出す時は ->monthlyTotal()
     */
    public function scopeMonthlyTotal($query, $date)
    {
        /**
         * 今月の初日を計算
         * 
         * ->copy() → 同じ日時の別インスタンスを作る
         * ->startOfMonth() → そのインスタンスの日付を月初に変更
         * ->toDateString() → 文字列に変換
         * 
         * $today->startOfMonth();
         * → これだと、$today自体の中身も変わっちゃうらしい。
         *   copy()で元の$todayを守ってくれてる
         */
        $start = $date->copy()->startOfMonth();

        // 今月の末日を計算
        $end = $date->copy()->endOfMonth();

        return $query->whereBetween('spent_at', [$start, $end])->sum('amount');
    }
}
