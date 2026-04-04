<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    protected $fillable = [
        'rol',
        'active'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id_rol', 'id_rol');
    }
}
