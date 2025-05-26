<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signature extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'cpf',
        'status',
        'plan_id',
        'cep',
        'address',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'phone',
        'company_name',
        'affiliate'
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function affiliate()
    {
        return $this->belongsTo(User::class, 'affiliate', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id_signature');
    }
}
