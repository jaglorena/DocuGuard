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
            'id_documento' => 'required|exists:documentos,id_documento',
            'nivel_acceso' => 'required|in:lectura,escritura,eliminacion',
        ]);

        Permiso::create($request->all());

        return redirect()->route('permisos.index')->with('success', 'Permiso asignado correctamente.');
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
        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'id_documento' => 'required|exists:documentos,id_documento',
            'nivel_acceso' => 'required|in:lectura,escritura,eliminacion',
        ]);

        $permiso = Permiso::findOrFail($id);
        $permiso->update($request->all());

        return redirect()->route('permisos.index')->with('success', 'Permiso actualizado correctamente.');
    }

    public function destroy($id)
    {
        $permiso = Permiso::findOrFail($id);
        $permiso->delete();

        return redirect()->route('Usuario.permisos.index')->with('success', 'Permiso eliminado correctamente.');
    }
}
