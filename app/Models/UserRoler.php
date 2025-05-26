<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRoler extends Model
{
    protected $fillable = [
        'user_id',
        'roles_id',
    ];
}
