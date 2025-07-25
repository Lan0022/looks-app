<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'category_id',
        'brand_id',
        'size',
        'color',
        'material',
        'weight',
        'status'
    ];
}
