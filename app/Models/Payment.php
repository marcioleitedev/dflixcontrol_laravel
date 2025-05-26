<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'signature_id',
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
