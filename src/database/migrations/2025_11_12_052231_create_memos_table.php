<?php

/**
 * migrationファイル
 * Laravelでデータベースのテーブル構造をコードで定義するファイル
 * 設計書兼スクリプトみたいなもの
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * up()メソッド
     * 
     * マイグレーションを「実行した」ときにやること
     * 
     * memosテーブルをこういう構成で作ってほしい
     * 
     * docker compose exec app php src/artisan migrate
     */
    public function up(): void
    {
        Schema::create('memos', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->text('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * down()メソッド
     * 
     * マイグレーションを「巻き戻す（ロールバック）」ときにやること
     * 
     * docker compose exec app php src/artisan migrate:rollback
     */
    public function down(): void
    {
        Schema::dropIfExists('memos');
    }
};
