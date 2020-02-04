<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trailer extends Model{
    protected $table = 'trailers';

    public function scopeDetalles($query){
        $query->select('trailers.*','conceptos_pago.descripcion as descripcion_concepto','cfdi_tipos_concepto_pago.descripcion as descripcion_cfdi')
        ->leftjoin('conceptos_pago',function($join){
            $join->on('trailers.tconcep','=','conceptos_pago.tconcep')->on('trailers.concepto','=','conceptos_pago.concepto')->on('trailers.ptaant','=','conceptos_pago.ptaant');
        })
        ->leftjoin('cfdi_tipos_concepto_pago',function($join){
            $join->on('conceptos_pago.clave_cfdi','=','cfdi_tipos_concepto_pago.clave')->on('conceptos_pago.tconcep','=','cfdi_tipos_concepto_pago.tipo_concepto');
        })
        ->orderBy('trailers.tconcep','ASC');
    }
}
