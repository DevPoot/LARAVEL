<?php

namespace App\Http\Controllers;

use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Equipos extends Controller
{
    /**
     * Muestra una lista de todos los equipos.
     */
    public function index()
    {
        // Trae todos los registros de la tabla equipos con sus proyectos relacionados
        $equipos = DB::table('equipos')
            ->join('proyectos', 'proyectos.id_proyecto', '=', 'equipos.Proyectos_id_proyecto')
            ->select(
                'equipos.id_equipo',
                'equipos.nombre_equipo',
                'equipos.Proyectos_id_proyecto',
                'proyectos.id_proyecto',
                'proyectos.nombre_proyecto'
            )
            ->get();
        
        return response()->json($equipos, 200);
    }

    /**
     * Guarda un nuevo equipo en la base de datos.
     */
    public function store(Request $request)
    {
        // 1. Validamos que los datos que envía el cliente sean los correctos
        $validados = $request->validate([
            'nombre_equipo'         => 'nullable|string|max:150',
            'Proyectos_id_proyecto' => 'required|integer' // Requerido porque no acepta nulos en tu BD
        ]);

        // 2. Creamos el registro en la base de datos con los datos validados
        $equipo = Equipo::create($validados);

        // 3. Devolvemos el equipo recién creado con un código 201 (Creado con éxito)
        return response()->json($equipo, 201);
    }

    /**
     * Muestra un solo equipo usando su ID.
     */
    public function show(string $id)
    {
        // Busca el equipo por su id_equipo con su proyecto relacionado
        $equipo = DB::table('equipos')
            ->join('proyectos', 'proyectos.id_proyecto', '=', 'equipos.Proyectos_id_proyecto')
            ->where('equipos.id_equipo', $id)
            ->select(
                'equipos.id_equipo',
                'equipos.nombre_equipo',
                'equipos.Proyectos_id_proyecto',
                'proyectos.id_proyecto',
                'proyectos.nombre_proyecto'
            )
            ->first();
        
        if (!$equipo) {
            return response()->json(['message' => 'Equipo no encontrado'], 404);
        }
        
        return response()->json($equipo, 200);
    }

    /**
     * Actualiza los datos de un equipo que ya existe.
     */
    public function update(Request $request, string $id)
    {
        // 1. Buscamos el equipo que queremos editar
        $equipo = Equipo::findOrFail($id);

        // 2. Validamos los datos nuevos (usamos 'sometimes' para que solo valide si el dato viene en la petición)
        $validados = $request->validate([
            'nombre_equipo'         => 'sometimes|nullable|string|max:150',
            'Proyectos_id_proyecto' => 'sometimes|required|integer'
        ]);

        // 3. Actualizamos los cambios en la base de datos
        $equipo->update($validados);

        // 4. Devolvemos el equipo con sus datos actualizados
        return response()->json($equipo, 200);
    }

    /**
     * Elimina un equipo de la base de datos.
     */
    public function destroy(string $id)
    {
        // 1. Buscamos el equipo que se quiere borrar
        $equipo = Equipo::findOrFail($id);
        
        // 2. Lo eliminamos
        $equipo->delete();

        // 3. Respondemos con un mensaje confirmando la eliminación
        return response()->json(['message' => 'Equipo eliminado correctamente'], 200);
    }
}