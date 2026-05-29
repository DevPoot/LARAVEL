<?php

use App\Http\Controllers\Equipos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
// })->middleware('auth:sanctum');

Route::apiResource('equipos', Equipos::class);
Route::apiResource('proyectos', 'App\Http\Controllers\ProyectoController');
Route::apiResource('tareas', 'App\Http\Controllers\TareaController');
Route::apiResource('sprint-proyecto', 'App\Http\Controllers\SprintProyectoController');
Route::apiResource('equipo-usuario', 'App\Http\Controllers\EquipoUsuarioController');
Route::apiResource('sprint-tarea', 'App\Http\Controllers\SprintTareaController');
Route::apiResource('sprint-tarea-usuario', 'App\Http\Controllers\SprintTareaUsuarioController');
Route::apiResource('sprints', 'App\Http\Controllers\SprintController');