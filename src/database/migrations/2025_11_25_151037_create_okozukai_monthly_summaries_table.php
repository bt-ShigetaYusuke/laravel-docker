<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('okozukai_monthly_summaries', function (Blueprint $table) {
            $table->id();

            /**
             * unique() → 同じ月は 1 回しか登録させない（重複禁止）
             */
            $table->char('year_month', 7)->unique(); // 例: "2025-01"

            $table->integer('budget');         // 月の予算額（基本 30000）
            $table->integer('total_spent');    // 月の支出合計
            $table->integer('remaining');      // 月の残り金額

            $table->integer('saving_added');   // 今月の貯金追加額（＝ remaining）
            $table->integer('total_saving');   // 貯金累計額（積み上げ）

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('okozukai_monthly_summaries');
    }
};
