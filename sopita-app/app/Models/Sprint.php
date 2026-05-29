<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sprint extends Model
{
    protected $table = 'sprints';

    // Tu llave primaria real
    protected $primaryKey = 'id_sprint'; 

    // Los campos permitidos para insertar
    protected $fillable = ['nombre_sprint', 'fecha_sprint', 'semana_sprint']; 

    public $timestamps = false; 
}