<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SprintTareaUsuarioController extends Controller
{
    // GET: http://localhost:8000/api/sprint-tarea-usuario
    public function index()
    {
        $asignaciones = DB::table('sprints_has_tareas_tickets_has_usuarios')
            ->leftJoin('usuarios', 'usuarios.id_usuario', '=', 'sprints_has_tareas_tickets_has_usuarios.usuarios_id_usuario')
            ->select(
                'sprints_has_tareas_tickets_has_usuarios.sprints_has_tareas_tickets_Sprints_id_sprint',
                'sprints_has_tareas_tickets_has_usuarios.sprints_has_tareas_tickets_Tareas_Tickets_id_tarea',
                'sprints_has_tareas_tickets_has_usuarios.usuarios_id_usuario',
                'usuarios.nombre',
                'usuarios.apellido',
                'usuarios.correo'
            )
            ->get();
        return response()->json($asignaciones, 200);
    }

    // POST: http://localhost:8000/api/sprint-tarea-usuario
    public function store(Request $request)
    {
        $validados = $request->validate([
            'sprints_has_tareas_tickets_Sprints_id_sprint'       => 'required|integer',
            'sprints_has_tareas_tickets_Tareas_Tickets_id_tarea' => 'required|integer',
            'usuarios_id_usuario'                                => 'required|integer'
        ]);

        // Verificar si la asignación ya existe
        $existe = DB::table('sprints_has_tareas_tickets_has_usuarios')
            ->where('sprints_has_tareas_tickets_Sprints_id_sprint', $validados['sprints_has_tareas_tickets_Sprints_id_sprint'])
            ->where('sprints_has_tareas_tickets_Tareas_Tickets_id_tarea', $validados['sprints_has_tareas_tickets_Tareas_Tickets_id_tarea'])
            ->where('usuarios_id_usuario', $validados['usuarios_id_usuario'])
            ->exists();

        if ($existe) {
            return response()->json([
                'message' => 'Este usuario ya está asignado a esta tarea en este sprint'
            ], 409);
        }

        DB::table('sprints_has_tareas_tickets_has_usuarios')->insert($validados);

        return response()->json([
            'message' => 'Asignación triple creada con éxito'
        ], 201);
    }

    // DELETE: http://localhost:8000/api/sprint-tarea-usuario/{sprint_id}/{tarea_id}/{usuario_id}
    public function destroy(string $sprint_id, string $tarea_id, string $usuario_id)
    {
        $eliminado = DB::table('sprints_has_tareas_tickets_has_usuarios')
            ->where('sprints_has_tareas_tickets_Sprints_id_sprint', $sprint_id)
            ->where('sprints_has_tareas_tickets_Tareas_Tickets_id_tarea', $tarea_id)
            ->where('usuarios_id_usuario', $usuario_id)
            ->delete();

        if (!$eliminado) {
            return response()->json(['message' => 'Asignación no encontrada'], 404);
        }

        return response()->json([
            'message' => 'Asignación eliminada correctamente'
        ], 200);
    }
}