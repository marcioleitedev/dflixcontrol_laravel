<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilAffiliate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $fillable = [
        'user_id',
        'cpf',
        'telefone',
        'data_nascimento',
        'sexo',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'link_afiliado',
        'foto_perfil',
        'descricao',
        'banco',
        'agencia',
        'conta',
        'tipo_conta',
        'pix',
        'comissao_percentual',
        'status',
    ];
}
