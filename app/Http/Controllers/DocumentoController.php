<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Permiso;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function index()
{
    /** @var Usuario $usuario */
    $usuario = Auth::user();

    if (strtolower($usuario->rol) === 'administrador') {
        $documentos = Documento::with('usuario')->get();
    } else {
        $documentos = Documento::whereHas('permisos', function ($query) use ($usuario) {
            $query->where('id_usuario', $usuario->id_usuario);
        })->with('usuario')->get();
    }

    return view('documentos.index', compact('documentos'));
    
    $usuarioId = $usuario->id_usuario;

    $documentos = Documento::whereHas('permisos', function ($query) use ($usuarioId) {
            $query->where('id_usuario', $usuarioId);
        })->get();

        return view('documentos.index', compact('documentos'));
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'archivo' => 'required|file|mimes:pdf,doc,docx,txt',
            'estado' => 'required|in:borrador,activo,archivado',
            'version' => 'required|string'
        ]);

        $rutaArchivo = $request->file('archivo')->store('documentos', 'public');

        $ultimaVersion = Documento::where('titulo', $request->titulo)->max('version');
        $nuevaVersion = $ultimaVersion ? $ultimaVersion + 1 : 1;

        $documento = Documento::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'ruta_archivo' => $rutaArchivo,
            'fecha_subida' => now(),
            'version' => $nuevaVersion,
            'estado' => $request->estado,
            'id_usuario_subida' => $usuario->id_usuario
        ]);
        

        foreach ($request->input('permisos', []) as $idUsuario => $niveles) {
            foreach ($niveles as $nivel) {
                Permiso::create([
                    'id_documento' => $documento->id_documento,
                    'id_usuario' => $idUsuario,
                    'nivel_acceso' => $nivel,
                ]);
            }
        }

        return redirect()->route('documentos.index')->with('success', 'Documento subido correctamente.');
    }

    public function show($id)
    {
        $documento = Documento::where('id_documento', $id)->firstOrFail();

        $versiones = Documento::where('titulo', $documento->titulo)
                            ->orderBy('fecha_subida', 'desc')
                            ->get();

        $permisos = $documento->permisos()->with('usuario')->get();

        return view('documentos.mostrar', compact('documento', 'versiones', 'permisos'));
    }
    
    public function edit($id)
    {
        $documento = Documento::where('id_documento', $id)->firstOrFail();
        $usuarios = Usuario::where('id_usuario', '!=', Auth::user()->id_usuario)->get();

        $permisosActuales = $documento->permisos->groupBy('id_usuario')->map(function ($grupo) {
            return $grupo->pluck('nivel_acceso')->toArray();
        });

        return view('documentos.editar', compact('documento', 'usuarios', 'permisosActuales'));
    }


    public function update(Request $request, $id)
    {
        $documento = Documento::where('id_documento', $id)->firstOrFail();

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:borrador,publicado'
        ]);

        $documento->update([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'estado' => $request->estado
        ]);

        Permiso::where('id_documento', $documento->id_documento)->delete();

        foreach ($request->input('permisos', []) as $idUsuario => $niveles) {
            foreach ($niveles as $nivel) {
                Permiso::create([
                    'id_documento' => $documento->id_documento,
                    'id_usuario' => $idUsuario,
                    'nivel_acceso' => $nivel,
                ]);
            }
        }        

        return redirect()->route('documentos.index')->with('success', 'Documento y permisos actualizados correctamente.');
    }

    public function destroy($id)
    {
        $documento = Documento::where('id_documento', $id)->firstOrFail();

        if ($documento->ruta_archivo) {
            Storage::disk('public')->delete($documento->ruta_archivo);
        }

        $documento->delete();

        return redirect()->route('documentos.index')->with('success', 'Documento eliminado correctamente.');
    }

    public function create(Request $request)
    {
        $usuarios = Usuario::where('id_usuario', '!=', Auth::user()->id_usuario)->get();
        $titulo = $request->get('titulo');

        $siguienteVersion = null;

        if ($titulo) {
            $ultimaVersion = Documento::where('titulo', $titulo)->max('version');
            $siguienteVersion = $ultimaVersion ? $ultimaVersion + 1 : 1;
        }

        return view('documentos.crear', compact('usuarios', 'titulo', 'siguienteVersion'));
    }
}
