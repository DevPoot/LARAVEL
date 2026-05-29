<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SprintTareaController extends Controller
{
    // GET: http://localhost:8000/api/sprint-tarea
    public function index()
    {
        $asignaciones = DB::table('sprints_has_tareas_tickets')
            ->leftJoin('usuarios', 'usuarios.id_usuario', '=', 'sprints_has_tareas_tickets.Usuarios_id_usuario')
            ->select(
                'sprints_has_tareas_tickets.Sprints_id_sprint',
                'sprints_has_tareas_tickets.Tareas_Tickets_id_tarea',
                'sprints_has_tareas_tickets.Tareas_Tickets_id_tarea_dependiente',
                'sprints_has_tareas_tickets.tipo_bloqueo_id_tipo_bloqueo',
                'sprints_has_tareas_tickets.fecha_bloqueo',
                'sprints_has_tareas_tickets.Usuarios_id_usuario',
                'usuarios.nombre',
                'usuarios.apellido',
                'usuarios.correo'
            )
            ->get();
        return response()->json($asignaciones, 200);
    }

    // POST: http://localhost:8000/api/sprint-tarea
    public function store(Request $request)
    {
        $validados = $request->validate([
            'Sprints_id_sprint'                    => 'required|integer',
            'Tareas_Tickets_id_tarea'              => 'required|integer',
            'Tareas_Tickets_id_tarea_dependiente'  => 'sometimes|nullable|integer',
            'tipo_bloqueo_id_tipo_bloqueo'         => 'sometimes|nullable|integer',
            'fecha_bloqueo'                        => 'sometimes|nullable|date',
            'Usuarios_id_usuario'                  => 'required|integer'
        ]);

        DB::table('sprints_has_tareas_tickets')->insert([
            'Sprints_id_sprint'                     => $validados['Sprints_id_sprint'],
            'Tareas_Tickets_id_tarea'               => $validados['Tareas_Tickets_id_tarea'],
            'Tareas_Tickets_id_tarea_dependiente'   => $validados['Tareas_Tickets_id_tarea_dependiente'] ?? null,
            'tipo_bloqueo_id_tipo_bloqueo'          => $validados['tipo_bloqueo_id_tipo_bloqueo'] ?? null,
            'fecha_bloqueo'                         => $validados['fecha_bloqueo'] ?? null,
            'Usuarios_id_usuario'                   => $validados['Usuarios_id_usuario']
        ]);

        return response()->json([
            'message' => 'Registro creado en sprints_has_tareas_tickets con éxito'
        ], 201);
    }

    // PUT: http://localhost:8000/api/sprint-tarea/{sprint_id}/{tarea_id}
    public function update(Request $request, string $sprint_id, string $tarea_id)
    {
        $validados = $request->validate([
            'Tareas_Tickets_id_tarea_dependiente'  => 'sometimes|nullable|integer',
            'tipo_bloqueo_id_tipo_bloqueo'         => 'sometimes|nullable|integer',
            'fecha_bloqueo'                        => 'sometimes|nullable|date',
            'Usuarios_id_usuario'                  => 'sometimes|integer'
        ]);

        $actualizado = DB::table('sprints_has_tareas_tickets')
            ->where('Sprints_id_sprint', $sprint_id)
            ->where('Tareas_Tickets_id_tarea', $tarea_id)
            ->update($validados);

        if (!$actualizado) {
            return response()->json(['message' => 'Asignación no encontrada'], 404);
        }

        return response()->json([
            'message' => 'Asignación actualizada correctamente'
        ], 200);
    }

    // DELETE: http://localhost:8000/api/sprint-tarea/{sprint_id}/{tarea_id}
    public function destroy(string $sprint_id, string $tarea_id)
    {
        $eliminado = DB::table('sprints_has_tareas_tickets')
            ->where('Sprints_id_sprint', $sprint_id)
            ->where('Tareas_Tickets_id_tarea', $tarea_id)
            ->delete();

        if (!$eliminado) {
            return response()->json(['message' => 'Asignación no encontrada'], 404);
        }

        return response()->json([
            'message' => 'Asignación eliminada correctamente'
        ], 200);
    }
}