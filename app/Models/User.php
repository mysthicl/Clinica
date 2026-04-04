<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
    use Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'id_user';
    protected $fillable = ['name', 'email', 'password', 'id_rol', 'active'];
    protected $hidden = ['password'];

    // Relaciones
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_rol', 'id_rol');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'id_user', 'id_user');
    }

    public function consults()
    {
        return $this->hasMany(Consult::class, 'id_user', 'id_user');
    }
}
