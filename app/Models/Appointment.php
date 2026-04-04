<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'id_appointment';
    protected $fillable = ['id_patient', 'id_user', 'scheduled_at', 'status', 'notes'];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'id_patient', 'id_patient');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function consult()
    {
        return $this->hasOne(Consult::class, 'id_appointment', 'id_appointment');
    }
}
