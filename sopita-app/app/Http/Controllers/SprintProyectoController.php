<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SprintProyectoController extends Controller
{
    // GET: http://localhost:8000/api/sprint-proyecto (Ver qué sprints están en qué proyectos)
    public function index()
    {
        $relaciones = DB::table('sprints_has_proyectos')
            ->select(
                'sprints_has_proyectos.sprints_id_sprint',
                'sprints_has_proyectos.proyectos_id_proyecto'
            )
            ->get();
        return response()->json($relaciones, 200);
    }

    // GET: http://localhost:8000/api/sprint-proyecto/{sprint_id}/{proyecto_id}
    public function show(string $sprint_id, string $proyecto_id)
    {
        $relacion = DB::table('sprints_has_proyectos')
            ->where('sprints_has_proyectos.sprints_id_sprint', $sprint_id)
            ->where('sprints_has_proyectos.proyectos_id_proyecto', $proyecto_id)
            ->select(
                'sprints_has_proyectos.sprints_id_sprint',
                'sprints_has_proyectos.proyectos_id_proyecto'
            )
            ->first();

        if (!$relacion) {
            return response()->json(['message' => 'Relación no encontrada'], 404);
        }

        return response()->json($relacion, 200);
    }

    // POST: http://localhost:8000/api/sprint-proyecto (Asociar un Sprint a un Proyecto)
    public function store(Request $request)
    {
        $validados = $request->validate([
            'sprints_id_sprint'      => 'required|integer',
            'proyectos_id_proyecto'  => 'required|integer'
        ]);

        // Verificar si la relación ya existe
        $existe = DB::table('sprints_has_proyectos')
            ->where('sprints_id_sprint', $validados['sprints_id_sprint'])
            ->where('proyectos_id_proyecto', $validados['proyectos_id_proyecto'])
            ->exists();

        if ($existe) {
            return response()->json([
                'message' => 'Este Sprint ya está asociado a este Proyecto'
            ], 409);
        }

        DB::table('sprints_has_proyectos')->insert($validados);

        return response()->json([
            'message' => 'Sprint asociado al Proyecto con éxito'
        ], 201);
    }

    // DELETE: http://localhost:8000/api/sprint-proyecto/{sprint_id}/{proyecto_id}
    public function destroy(string $sprint_id, string $proyecto_id)
    {
        $eliminado = DB::table('sprints_has_proyectos')
            ->where('sprints_id_sprint', $sprint_id)
            ->where('proyectos_id_proyecto', $proyecto_id)
            ->delete();

        if (!$eliminado) {
            return response()->json(['message' => 'Relación no encontrada'], 404);
        }

        return response()->json([
            'message' => 'Sprint desasociado del Proyecto correctamente'
        ], 200);
    }
}