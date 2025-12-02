<?php

/**
 * okozukai_expenses テーブルの設計図
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('okozukai_expenses', function (Blueprint $table) {
            $table->id();
            // 支出した日付を保存するカラム
            $table->date('spent_at');
            $table->integer('amount'); // 使った金額を保存する整数のカラム
            /**
             * okozukai_category_id という外部キー（カテゴリ ID）を持つ
             * nullable() → カテゴリ未選択でも OK
             * constrained('okozukai_categories') → カテゴリテーブルと紐づくよ
             * nullOnDelete() → カテゴリ側が消されたら、この値は NULL にして残す
             * → カテゴリが消えても、この支出のデータ自体は残るようにしてる、ってこと
             */
            $table->foreignId('okozukai_category_id')
                ->nullable()
                ->constrained('okozukai_categories')
                ->nullOnDelete();
            // created_at, updated_atを自動で作成してくれる
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('okozukai_expenses');
    }
};
