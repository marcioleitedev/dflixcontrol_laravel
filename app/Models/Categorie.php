<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $fillable = ['name', 'description', 'parent_id' , 'id_signature' , 'id_company'];

    // Subcategorias desta categoria
    public function children()
    {
        return $this->hasMany(Categorie::class, 'parent_id');
    }

    // Categoria pai (caso seja uma subcategoria)
    public function parent()
    {
        return $this->belongsTo(Categorie::class, 'parent_id');
    }

    // Produtos ligados a esta categoria ou subcategoria
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}

