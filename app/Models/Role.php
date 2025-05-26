<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'level',
        'bottom_text',
        'link_url',
        'icon',
    ];
}
