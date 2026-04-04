<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'id_service';
    protected $fillable = ['name', 'price'];

    public function consultServices()
    {
        return $this->hasMany(ConsultService::class, 'id_service', 'id_service');
    }
}
