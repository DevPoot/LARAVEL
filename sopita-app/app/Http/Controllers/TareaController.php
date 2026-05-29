<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TareaController extends Controller
{
    // GET: http://localhost:8000/api/tareas
    public function index()
    {
        // Trae todas las tareas disponibles
        $tareas = DB::table('tareas_tickets')
            ->select(
                'tareas_tickets.id_tarea',
                'tareas_tickets.nombre_tarea',
                'tareas_tickets.tipo_tarea_id_tipo_tarea',
                'tareas_tickets.prioridad',
                'tareas_tickets.estado',
                'tareas_tickets.estimacion'
            )
            ->get();

        return response()->json($tareas, 200);
    }

    // GET: http://localhost:8000/api/tareas/{id}
    public function show(string $id)
    {
        $tarea = DB::table('tareas_tickets')
            ->where('tareas_tickets.id_tarea', $id)
            ->select(
                'tareas_tickets.id_tarea',
                'tareas_tickets.nombre_tarea',
                'tareas_tickets.tipo_tarea_id_tipo_tarea',
                'tareas_tickets.prioridad',
                'tareas_tickets.estado',
                'tareas_tickets.estimacion'
            )
            ->first();

        if (!$tarea) {
            return response()->json(['message' => 'Tarea no encontrada'], 404);
        }

        return response()->json($tarea, 200);
    }

    // POST: http://localhost:8000/api/tareas
    public function store(Request $request)
    {
        $validados = $request->validate([
            'nombre_tarea'             => 'required|string|max:255',
            'tipo_tarea_id_tipo_tarea' => 'required|integer',
            'prioridad'                => 'required|string',
            'estado'                   => 'sometimes|string|default:Pendiente',
            'estimacion'               => 'sometimes|numeric'
        ]);

        $tarea = Tarea::create($validados);

        return response()->json($tarea, 201);
    }

    // PUT: http://localhost:8000/api/tareas/{id}
    public function update(Request $request, string $id)
    {
        $tarea = Tarea::findOrFail($id);

        $validados = $request->validate([
            'nombre_tarea'             => 'sometimes|string|max:255',
            'tipo_tarea_id_tipo_tarea' => 'sometimes|integer',
            'prioridad'                => 'sometimes|string',
            'estado'                   => 'sometimes|string',
            'estimacion'               => 'sometimes|numeric'
        ]);

        $tarea->update($validados);

        return response()->json($tarea, 200);
    }

    // DELETE: http://localhost:8000/api/tareas/{id}
    public function destroy(string $id)
    {
        $tarea = Tarea::findOrFail($id);
        $tarea->delete();

        return response()->json(['message' => 'Tarea eliminada correctamente'], 200);
    }
}