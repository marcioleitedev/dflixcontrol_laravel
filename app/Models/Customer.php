<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'document',
        'document_type',
        'postal_code',
        'address',
        'address_number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'birth_date',
        'gender',
        'notes',
        'is_active',
        'signature_id'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function signature()
    {
        return $this->belongsTo(Signature::class);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    // Escopos úteis
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch($query, $term)
    {
        return $query->where('name', 'like', "%{$term}%")
            ->orWhere('document', 'like', "%{$term}%")
            ->orWhere('email', 'like', "%{$term}%")
            ->orWhere('phone', 'like', "%{$term}%");
    }
}
