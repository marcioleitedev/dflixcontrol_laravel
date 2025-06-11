<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'product_id',
        'type',
        'description',
        'affiliate_link',
        'affiliate_store',
        'price',
        'original_price',
        'quantity',
        'discount',
        'total',
        'notes',
        'position',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Get the budget that owns the item.
     */
    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    /**
     * Get the product (if it's from inventory).
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Determine if the item is an affiliate product.
     */
    public function isAffiliate(): bool
    {
        return $this->type === 'affiliate';
    }

    /**
     * Calculate total price.
     */
    public function calculateTotal(): void
    {
        $this->total = ($this->price * $this->quantity) - $this->discount;
    }
}
