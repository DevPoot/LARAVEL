<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    // 1. Laravel por defecto busca una tabla llamada "equipos", pero es mejor asegurarlo:
    protected $table = 'equipos';

    // 2. IMPORTANTÍSIMO: Tu llave primaria se llama 'id_equipo', no 'id'
    protected $primaryKey = 'id_equipo';

    // 3. En la estructura de tu base de datos no vi los campos 'created_at' y 'updated_at'. 
    // Ponemos esto en false para que Laravel no intente buscarlos ni llenarlos automáticamente.
    public $timestamps = false; 

    // 4. Aquí defines qué columnas permites que se puedan llenar mediante un formulario o API:
    protected $fillable = [
        'nombre_equipo',
        'Proyectos_id_proyecto'
    ];
}