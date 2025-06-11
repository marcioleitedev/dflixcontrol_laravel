<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'vehicle_id',
        'budget_number',
        'date',
        'expiry_date',
        'total',
        'discount',
        'tax',
        'description',
        'notes',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'expiry_date' => 'date',
        'total' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
    ];

    /**
     * Get the customer that owns the budget.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the vehicle that owns the budget.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function items()
    {
        return $this->hasMany(BudgetItem::class)->orderBy('position');
    }

    public function calculateTotal(): void
    {
        $this->total = $this->items->sum('total');
        $this->save();
    }
}
