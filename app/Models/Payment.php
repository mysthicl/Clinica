<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'id_payment';

    protected $fillable = [
        'id_consult',
        'amount',
        'status',
        'payment_date'
    ];

    public function consult()
    {
        return $this->belongsTo(Consult::class, 'id_consult', 'id_consult');
    }
}
