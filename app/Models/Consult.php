<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consult extends Model
{
    protected $table = 'consults';
    protected $primaryKey = 'id_consult';
    protected $fillable = ['id_patient', 'id_user', 'date_register', 'total', 'status', 'id_appointment', 'notes'];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'id_patient', 'id_patient');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'id_appointment', 'id_appointment');
    }

    public function services()
    {
        return $this->hasMany(ConsultService::class, 'id_consult', 'id_consult');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'id_consult', 'id_consult');
    }
}
