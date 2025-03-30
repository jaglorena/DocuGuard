<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use App\Models\Usuario;
use App\Models\Documento;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    public function index()
    {
        $permisos = Permiso::with(['usuario', 'documento'])->get();
        return view('Usuario.permisos.index', compact('permisos'));
    }

    public function create()
    {
        $usuarios = Usuario::all();
        $documentos = Documento::all();
        return view('Usuario.permisos.create', compact('usuarios', 'documentos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'id_documento' => 'required|array|min:1',
            'id_documento.*' => 'exists:documentos,id_documento',
            'nivel_acceso' => 'required|array|min:1',
            'nivel_acceso.*' => 'in:lectura,escritura,eliminacion',
        ]);        
        
        foreach ($request->id_documento as $documentoId) {
            foreach ($request->nivel_acceso as $permiso) {
                Permiso::firstOrCreate([
                    'id_usuario' => $request->id_usuario,
                    'id_documento' => $documentoId,
                    'nivel_acceso' => $permiso
                ]);
            }
        }

        return redirect()->route('permisos.index')->with('success', 'Permisos asignados correctamente.');
    }


    public function edit($id)
    {
        $permiso = Permiso::findOrFail($id);
        $usuarios = Usuario::all();
        $documentos = Documento::all();
        return view('Usuario.permisos.edit', compact('permiso', 'usuarios', 'documentos'));
    }

    public function update(Request $request, $id)
    {
        $permiso = Permiso::findOrFail($id);

        // Eliminar TODOS los permisos del mismo usuario y documento
        Permiso::where('id_usuario', $permiso->id_usuario)
            ->where('id_documento', $permiso->id_documento)
            ->delete();

        // Crear los nuevos permisos seleccionados
        foreach ($request->nivel_acceso as $nivel) {
            Permiso::create([
                'id_usuario' => $permiso->id_usuario,
                'id_documento' => $permiso->id_documento,
                'nivel_acceso' => $nivel,
            ]);
        }

        return redirect()->route('permisos.index')->with('success', 'Permisos actualizados correctamente.');
    }


    public function destroy($id)
    {
        $permiso = Permiso::findOrFail($id);
        $permiso->delete();

        return redirect()->route('Usuario.permisos.index')->with('success', 'Permiso eliminado correctamente.');
    }
}
