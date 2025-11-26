<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OkozukaiCategory extends Model
{
    public function expenses()
    {
        return $this->hasMany(OkozukaiExpense::class, 'okozukai_category_id');
    }
}
