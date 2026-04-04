<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultService extends Model
{
    protected $table = 'consult_service';
    protected $primaryKey = 'id_consult_service';

    protected $fillable = [
        'id_consult',
        'id_service',
        'price',
        'discount',
        'final_price'
    ];

    public function consult()
    {
        return $this->belongsTo(Consult::class, 'id_consult', 'id_consult');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'id_service', 'id_service');
    }
}
