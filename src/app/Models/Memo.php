<?php

/**
 * Laravel の「Eloquent モデル」
 * 
 * DBのテーブル（memos）とコードをつなぐ橋渡しをしている
 */

// このファイルの「場所」を示している
namespace App\Models;

/**
 * Laravel の ORM（Eloquent）である Model クラスを使うための use 文。
 * これを継承することで、DB操作（保存・更新・削除・検索）をめっちゃシンプルにできる。
 */

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Memo モデルを定義
 * 
 * Eloquentはクラス名の複数形を自動でテーブル名にするから、
 * このクラスは自動的に memos テーブルと紐づく。
 * 
 * 「Memo::all()」って書くと → SELECT * FROM memos; が実行される。
 */
class Memo extends Model
{
    /**
     * Factory 機能を使うためのトレイト
     * 
     * テストやダミーデータを作成するのに必要
     */
    use HasFactory;

    /**
     * 「一括代入していいカラム」を指定している
     */
    protected $fillable = ['title', 'content'];
}
