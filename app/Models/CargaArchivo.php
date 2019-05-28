<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CargaArchivo extends Model{
    protected $table = 'cargas_archivos';
    protected $fillable = ['batch','nombre_archivo_tra','nombre_archivo_dat','qnareal','anioreal','nomprod'];
    protected $primaryKey = 'batch';
}
