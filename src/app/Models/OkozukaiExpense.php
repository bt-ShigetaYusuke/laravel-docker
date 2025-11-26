<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OkozukaiExpense extends Model
{
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
