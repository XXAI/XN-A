<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dato extends Model{
    protected $table = 'datos';

    public function desglose(){
        return $this->hasMany('App\Models\Trailer','llave_dato','llave');
    }

    public function desgloseDetalles(){
        return $this->hasMany('App\Models\Trailer','llave_dato','llave')->detalles();
    }
}
