<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    //
    protected $fillable = [
        'id',
        'product_id',
        'image_url',
        'alt_text',
        'is_primary',
        'sort_order',
        'created_at',
        'updated_at'
    ];
}
