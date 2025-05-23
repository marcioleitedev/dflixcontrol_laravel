<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'subscription_id',
        'value',
        'payment_method',
        'status',
        'transaction_id',
        'limit_date'
    ];

    protected $casts = [
        'value' => 'float',
        'status' => 'boolean',
        'limit_date' => 'date',
    ];
}
