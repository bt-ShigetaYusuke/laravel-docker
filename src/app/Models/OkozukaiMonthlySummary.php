<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OkozukaiMonthlySummary extends Model
{
    protected $fillable = [
        'year_month',
        'budget',
        'total_spent',
        'remaining',
        'saving_added',
        'total_saving',
    ];
}
