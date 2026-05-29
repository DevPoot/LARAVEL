<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = 'proyectos';

    // 1. LE DECIMOS A LARAVEL CUÁL ES TU ID REAL
    protected $primaryKey = 'id_proyecto'; 

    // 2. COLUMNAS REALES DE TU TABLA
    protected $fillable = ['nombre_proyecto']; 

    public $timestamps = false; 
}