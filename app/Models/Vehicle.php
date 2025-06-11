<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'signature_id',
        'plate',
        'brand',
        'model',
        'version',
        'year',
        'color',
        'chassis',
        'mileage',
        'fuel_type',
        'observations',
    ];

    protected $casts = [
        'year' => 'integer',
        'mileage' => 'integer',
    ];

    /**
     * Get the customer that owns the vehicle.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function signature()
    {
        return $this->belongsTo(Signature::class, 'signature_id');
    }
}
