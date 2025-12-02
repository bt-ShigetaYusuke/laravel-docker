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
            if (empty($expense->spent_at)) {
                $expense->spent_at = now()->toDateString();
            }
        });
    }

    protected $casts = [
        'spent_at' => 'date',
    ];

    protected $fillable = [
        'spent_at',
        'amount',
        'okozukai_category_id',
    ];


    public function category()
    {
        return $this->belongsTo(OkozukaiCategory::class, 'okozukai_category_id');
    }
}
