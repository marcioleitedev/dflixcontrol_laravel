<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'contact_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'postal_code',
        'country',
        'cnpj_cpf',
        'ie',
        'notes',
        'payment_terms',
        'id_signature',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    // Relacionamento com a assinatura (se aplicável)
    public function signature()
    {
        return $this->belongsTo(Signature::class, 'id_signature');
    }

    // Relacionamento com produtos (se necessário)
    public function products()
    {
        return $this->hasMany(Product::class, 'supplier_id');
    }
}