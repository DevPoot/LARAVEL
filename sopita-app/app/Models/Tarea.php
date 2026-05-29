<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    // Tu tabla real según vimos en la lista anterior
    protected $table = 'tareas_tickets';

    // Tu llave primaria real
    protected $primaryKey = 'id_tarea'; 

    // Los campos que existen en tu tabla y permitiremos registrar
    protected $fillable = [
        'nombre_tarea', 
        'tipo_tarea_id_tipo_tarea', 
        'prioridad', 
        'estado', 
        'estimacion'
    ]; 

    public $timestamps = false; 
}