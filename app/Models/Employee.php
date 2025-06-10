<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        // Informações pessoais
        'name',
        'cpf',
        'rg',
        'birth_date',
        'gender',

        // Dados de contato
        'email',
        'phone',
        'emergency_phone',

        // Endereço
        'postal_code',
        'address',
        'address_number',
        'complement',
        'neighborhood',
        'city',
        'state',

        // Informações profissionais
        'position',
        'department',
        'salary',
        'hire_date',
        'termination_date',
        'status',

        // Documentos
        'pis',
        'ctps_number',
        'ctps_series',

        // Relacionamento
        'signature_id'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
        'termination_date' => 'date',
        'salary' => 'decimal:2',
    ];

    // Relacionamento com signature (empresa)
    public function signature()
    {
        return $this->belongsTo(Signature::class);
    }

    // Escopos úteis
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOnLeave($query)
    {
        return $query->where('status', 'on_leave');
    }

    public function scopeTerminated($query)
    {
        return $query->where('status', 'terminated');
    }

    // Mutators/accessors
    public function setCpfAttribute($value)
    {
        $this->attributes['cpf'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function getFormattedCpfAttribute()
    {
        $cpf = $this->cpf;
        return substr($cpf, 0, 3) . '.' .
            substr($cpf, 3, 3) . '.' .
            substr($cpf, 6, 3) . '-' .
            substr($cpf, 9, 2);
    }

    public function getFormattedPhoneAttribute()
    {
        $phone = $this->phone;
        // Formatação de telefone (adaptar conforme necessidade)
        if (strlen($phone) === 11) {
            return '(' . substr($phone, 0, 2) . ') ' .
                substr($phone, 2, 5) . '-' .
                substr($phone, 7, 4);
        }
        return $phone;
    }
}
