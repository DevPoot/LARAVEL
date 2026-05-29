<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipoUsuarioController extends Controller
{
    // GET: http://localhost:8000/api/equipo-usuario (Ver qué usuarios están en qué equipos)
    public function index()
    {
        $relaciones = DB::table('equipos_has_usuarios')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'equipos_has_usuarios.Usuarios_id_usuario')
            ->join('equipos', 'equipos.id_equipo', '=', 'equipos_has_usuarios.Equipos_id_equipo')
            ->join('roles', 'roles.id_rol', '=', 'equipos_has_usuarios.Roles_id_rol')
            ->select(
                'equipos_has_usuarios.Equipos_id_equipo',
                'equipos_has_usuarios.Usuarios_id_usuario',
                'equipos_has_usuarios.Roles_id_rol',
                'usuarios.id_usuario',
                'usuarios.nombre',
                'usuarios.apellido',
                'usuarios.correo',
                'equipos.id_equipo',
                'equipos.nombre_equipo',
                'equipos.Proyectos_id_proyecto',
                'roles.id_rol',
                'roles.nombre_rol'
            )
            ->get();

        return response()->json($relaciones, 200);
    }

    // GET: http://localhost:8000/api/equipo-usuario/{id_equipo}/{id_usuario}
    public function show(string $id_equipo, string $id_usuario)
    {
        $relacion = DB::table('equipos_has_usuarios')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'equipos_has_usuarios.Usuarios_id_usuario')
            ->join('equipos', 'equipos.id_equipo', '=', 'equipos_has_usuarios.Equipos_id_equipo')
            ->join('roles', 'roles.id_rol', '=', 'equipos_has_usuarios.Roles_id_rol')
            ->where('equipos_has_usuarios.Equipos_id_equipo', $id_equipo)
            ->where('equipos_has_usuarios.Usuarios_id_usuario', $id_usuario)
            ->select(
                'equipos_has_usuarios.Equipos_id_equipo',
                'equipos_has_usuarios.Usuarios_id_usuario',
                'equipos_has_usuarios.Roles_id_rol',
                'usuarios.id_usuario',
                'usuarios.nombre',
                'usuarios.apellido',
                'usuarios.correo',
                'equipos.id_equipo',
                'equipos.nombre_equipo',
                'equipos.Proyectos_id_proyecto',
                'roles.id_rol',
                'roles.nombre_rol'
            )
            ->first();

        if (!$relacion) {
            return response()->json(['message' => 'Relación no encontrada'], 404);
        }

        return response()->json($relacion, 200);
    }

    // POST: http://localhost:8000/api/equipo-usuario (Asociar un Usuario a un Equipo con un Rol)
    public function store(Request $request)
    {
        $validados = $request->validate([
            'Equipos_id_equipo'   => 'required|integer',
            'Usuarios_id_usuario' => 'required|integer',
            'Roles_id_rol'        => 'sometimes|integer'
        ]);

        // Verificar si la relación ya existe
        $existe = DB::table('equipos_has_usuarios')
            ->where('Equipos_id_equipo', $validados['Equipos_id_equipo'])
            ->where('Usuarios_id_usuario', $validados['Usuarios_id_usuario'])
            ->exists();

        if ($existe) {
            return response()->json([
                'message' => 'El usuario ya está asignado a este equipo'
            ], 409);
        }

        DB::table('equipos_has_usuarios')->insert([
            'Equipos_id_equipo'   => $validados['Equipos_id_equipo'],
            'Usuarios_id_usuario' => $validados['Usuarios_id_usuario'],
            'Roles_id_rol'        => $validados['Roles_id_rol'] ?? 1
        ]);

        return response()->json([
            'message' => 'Usuario asignado al equipo con éxito'
        ], 201);
    }

    // PUT: http://localhost:8000/api/equipo-usuario/{id_equipo}/{id_usuario}
    public function update(Request $request, string $id_equipo, string $id_usuario)
    {
        $validados = $request->validate([
            'Roles_id_rol' => 'sometimes|integer'
        ]);

        $actualizado = DB::table('equipos_has_usuarios')
            ->where('Equipos_id_equipo', $id_equipo)
            ->where('Usuarios_id_usuario', $id_usuario)
            ->update($validados);

        if (!$actualizado) {
            return response()->json(['message' => 'Relación no encontrada'], 404);
        }

        return response()->json([
            'message' => 'Rol del usuario actualizado correctamente'
        ], 200);
    }

    // DELETE: http://localhost:8000/api/equipo-usuario/{id_equipo}/{id_usuario}
    public function destroy(string $id_equipo, string $id_usuario)
    {
        $eliminado = DB::table('equipos_has_usuarios')
            ->where('Equipos_id_equipo', $id_equipo)
            ->where('Usuarios_id_usuario', $id_usuario)
            ->delete();

        if (!$eliminado) {
            return response()->json(['message' => 'Relación no encontrada'], 404);
        }

        return response()->json([
            'message' => 'Usuario removido del equipo correctamente'
        ], 200);
    }
}