<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'patients';
    protected $primaryKey = 'id_patient';
    protected $fillable = ['name', 'last_name', 'dui', 'phone', 'address', 'birthdate'];

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'id_patient', 'id_patient');
    }

    public function consults()
    {
        return $this->hasMany(Consult::class, 'id_patient', 'id_patient');
    }
}
