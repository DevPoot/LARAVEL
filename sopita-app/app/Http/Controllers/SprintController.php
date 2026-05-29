<?php

namespace App\Http\Controllers;

use App\Models\Sprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SprintController extends Controller
{
    // GET: http://localhost:8000/api/sprints
    public function index()
    {
        // Trae todos los sprints con información de los proyectos relacionados
        $sprints = DB::table('sprints')
            ->leftJoin('sprints_has_proyectos', 'sprints_has_proyectos.sprints_id_sprint', '=', 'sprints.id_sprint')
            ->leftJoin('proyectos', 'proyectos.id_proyecto', '=', 'sprints_has_proyectos.proyectos_id_proyecto')
            ->select(
                'sprints.id_sprint',
                'sprints.nombre_sprint',
                'sprints.fecha_sprint',
                'sprints.semana_sprint',
                'proyectos.id_proyecto',
                'proyectos.nombre_proyecto'
            )
            ->get();

        return response()->json($sprints, 200);
    }

    // GET: http://localhost:8000/api/sprints/{id}
    public function show(string $id)
    {
        $sprint = DB::table('sprints')
            ->where('sprints.id_sprint', $id)
            ->leftJoin('sprints_has_proyectos', 'sprints_has_proyectos.sprints_id_sprint', '=', 'sprints.id_sprint')
            ->leftJoin('proyectos', 'proyectos.id_proyecto', '=', 'sprints_has_proyectos.proyectos_id_proyecto')
            ->select(
                'sprints.id_sprint',
                'sprints.nombre_sprint',
                'sprints.fecha_sprint',
                'sprints.semana_sprint',
                'proyectos.id_proyecto',
                'proyectos.nombre_proyecto'
            )
            ->first();

        if (!$sprint) {
            return response()->json(['message' => 'Sprint no encontrado'], 404);
        }

        return response()->json($sprint, 200);
    }

    // POST: http://localhost:8000/api/sprints
    public function store(Request $request)
    {
        $validados = $request->validate([
            'nombre_sprint' => 'required|string|max:150',
            'fecha_sprint'  => 'required|date',
            'semana_sprint' => 'required|integer'
        ]);

        $sprint = Sprint::create($validados);

        return response()->json($sprint, 201);
    }

    // PUT: http://localhost:8000/api/sprints/{id}
    public function update(Request $request, string $id)
    {
        $sprint = Sprint::findOrFail($id);

        $validados = $request->validate([
            'nombre_sprint' => 'sometimes|string|max:150',
            'fecha_sprint'  => 'sometimes|date',
            'semana_sprint' => 'sometimes|integer'
        ]);

        $sprint->update($validados);

        return response()->json($sprint, 200);
    }

    // DELETE: http://localhost:8000/api/sprints/{id}
    public function destroy(string $id)
    {
        $sprint = Sprint::findOrFail($id);
        $sprint->delete();

        return response()->json(['message' => 'Sprint eliminado correctamente'], 200);
    }
}