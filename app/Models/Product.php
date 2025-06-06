<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'unit',
        'brand',
        'cost_price',
        'sale_price',
        'quantity',
        'minimum_stock',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }
}
