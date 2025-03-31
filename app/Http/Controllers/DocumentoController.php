<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Permiso;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Enums\EstadoDocumento;
use Illuminate\Validation\Rule;

class DocumentoController extends Controller
{
    private function obtenerGrupoDesdeTitulo($titulo)
    {
        preg_match('/\[grupo=(.*?)\]/', $titulo, $matches);
        return $matches[1] ?? trim($titulo);
    }
    
    public function index()
    {
        $usuario = Auth::user();

        $query = Documento::query();

        if (strtolower($usuario->rol) !== 'administrador') {
            $query->whereHas('permisos', function ($q) use ($usuario) {
                $q->where('id_usuario', $usuario->id_usuario);
            });
        }

        $documentosAccesibles = $query->get();

        $agrupados = $documentosAccesibles->groupBy(function ($doc) {
            preg_match('/\[grupo=(.*?)\]/', $doc->titulo, $match);
            return $match[1] ?? $doc->titulo;
        });

        $documentos = $agrupados->map(function ($grupo) {
            return $grupo->sortByDesc('version')->first();
        });

        return view('documentos.index', [
            'documentos' => $documentos
        ]);
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'archivo' => 'required|file|mimes:pdf,doc,docx,txt',
            'estado' => ['required', Rule::in(array_column(EstadoDocumento::cases(), 'value'))],
        ]);

        if ($request->filled('grupo_existente')) {
            $grupo = $request->grupo_existente;
            $tituloEditable = $request->titulo;
        } else {
            $grupo = uniqid();
            $tituloEditable = $request->titulo;
        }
        

        $tituloFinal = "{$tituloEditable} [grupo={$grupo}]";

        $ultimaVersion = Documento::where('titulo', 'like', "%[grupo={$grupo}]%")->max('version');
        $nuevaVersion = $ultimaVersion ? $ultimaVersion + 1 : 1;

        $rutaArchivo = $request->file('archivo')->store('documentos', 'public');

        $documento = Documento::create([
            'titulo' => $tituloFinal,
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
        return redirect()->route('documentos.show', $documento->id_documento)
                 ->with('success', 'Documento subido correctamente.');
    }


    public function show($id)
    {
        $documento = Documento::with('usuario')->findOrFail($id);

        $grupo = $this->obtenerGrupoDesdeTitulo($documento->titulo);
        $versiones = Documento::where('titulo', 'like', "%[grupo={$grupo}]%")
            ->orderByDesc('version')
            ->get();

        $permisos = $documento->permisos()->with('usuario')->get();

        return view('documentos.mostrar', compact('documento', 'versiones', 'permisos'));
    }


    public function edit($id)
    {
        $documento = Documento::findOrFail($id);
        $usuarios = Usuario::where('id_usuario', '!=', Auth::user()->id_usuario)->get();

        $permisosActuales = $documento->permisos->groupBy('id_usuario')->map(function ($grupo) {
            return $grupo->pluck('nivel_acceso')->toArray();
        });

        return view('documentos.editar', compact('documento', 'usuarios', 'permisosActuales'));
    }


    public function update(Request $request, $id)
    {
        $documento = Documento::findOrFail($id);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|string',
        ]);

        // Extraer el grupo actual
        $grupo = $this->obtenerGrupoDesdeTitulo($documento->titulo);
        $tituloEditable = $request->titulo;

        $documento->titulo = "{$tituloEditable} [grupo={$grupo}]";
        $documento->descripcion = $request->descripcion;
        $documento->estado = $request->estado;
        $documento->save();

        // Actualizar permisos
        Permiso::where('id_documento', $documento->id_documento)->delete();

        if ($request->has('permisos')) {
            foreach ($request->input('permisos', []) as $idUsuario => $niveles) {
                foreach ($niveles as $nivel) {
                    Permiso::create([
                        'id_documento' => $documento->id_documento,
                        'id_usuario' => $idUsuario,
                        'nivel_acceso' => $nivel,
                    ]);
                }
            }
        }

        return redirect()->route('documentos.show', $documento->id_documento)
                        ->with('success', 'Documento actualizado correctamente.');
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
        
        $titulo = $request->input('titulo');
        $nuevaVersion = '0001';

        if ($titulo) {
            $ultimaVersion = Documento::where('titulo', $titulo)->max('version');
            $siguiente = (int)$ultimaVersion + 1;
            $nuevaVersion = str_pad($siguiente, 4, '0', STR_PAD_LEFT);
        }

        $documentosExistentes = Documento::all()->groupBy(function ($doc) {
            preg_match('/\[grupo=(.*?)\]/', $doc->titulo, $match);
            return $match[1] ?? $doc->titulo;
        })->map->first();
        
        return view('documentos.crear', compact('usuarios', 'titulo', 'nuevaVersion', 'documentosExistentes'));
        
    }
}
