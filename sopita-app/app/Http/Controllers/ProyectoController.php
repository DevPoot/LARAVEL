<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProyectoController extends Controller
{
    // GET: http://localhost:8000/api/proyectos
    public function index()
    {
        // Trae todos los proyectos con información de sus equipos relacionados
        $proyectos = DB::table('proyectos')
            ->leftJoin('equipos', 'equipos.Proyectos_id_proyecto', '=', 'proyectos.id_proyecto')
            ->select(
                'proyectos.id_proyecto',
                'proyectos.nombre_proyecto',
                DB::raw('COUNT(equipos.id_equipo) as cantidad_equipos')
            )
            ->groupBy('proyectos.id_proyecto', 'proyectos.nombre_proyecto')
            ->get();
        
        return response()->json($proyectos, 200);
    }

    // GET: http://localhost:8000/api/proyectos/{id}
    public function show(string $id)
    {
        $proyecto = DB::table('proyectos')
            ->where('proyectos.id_proyecto', $id)
            ->select('proyectos.id_proyecto', 'proyectos.nombre_proyecto')
            ->first();

        if (!$proyecto) {
            return response()->json(['message' => 'Proyecto no encontrado'], 404);
        }

        return response()->json($proyecto, 200);
    }

    // POST: http://localhost:8000/api/proyectos
    public function store(Request $request)
    {
        // Guardamos usando el nombre exacto de tu columna
        $proyecto = Proyecto::create([
            'nombre_proyecto' => $request->nombre_proyecto
        ]);

        return response()->json($proyecto, 201);
    }

    // PUT: http://localhost:8000/api/proyectos/{id}
    public function update(Request $request, string $id)
    {
        $proyecto = Proyecto::findOrFail($id);

        $validados = $request->validate([
            'nombre_proyecto' => 'sometimes|string|max:150'
        ]);

        $proyecto->update($validados);

        return response()->json($proyecto, 200);
    }

    // DELETE: http://localhost:8000/api/proyectos/{id}
    public function destroy(string $id)
    {
        $proyecto = Proyecto::findOrFail($id);
        $proyecto->delete();

        return response()->json(['message' => 'Proyecto eliminado correctamente'], 200);
    }
}
