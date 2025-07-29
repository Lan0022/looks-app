<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    protected $table = 'product_reviews';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
