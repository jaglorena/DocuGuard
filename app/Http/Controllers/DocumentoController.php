<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function index()
{
    /** @var Usuario $usuario */
      $usuario = Auth::user();
    $usuarioId = 1;
    /**$usuarioId = $usuario->id_usuario;*/

    $documentos = Documento::whereHas('permisos', function ($query) use ($usuarioId) {
            $query->where('id_usuario', $usuarioId);
        })->get();

        return view('documentos.index', compact('documentos'));
    }

    public function store(Request $request)
    {
        /**$usuario = Auth::user();*/

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'archivo' => 'required|file|mimes:pdf,doc,docx,txt',
            'estado' => 'required|in:borrador,publicado',
            'version' => 'required|string'
        ]);

        $rutaArchivo = $request->file('archivo')->store('documentos', 'public');

        Documento::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'ruta_archivo' => $rutaArchivo,
            'fecha_subida' => now(),
            'version' => $request->version,
            'estado' => $request->estado,
            /*'id_usuario_subida' => $usuario->id_usuario*/
            'id_usuario_subida' => '1',
        ]);

        return redirect()->route('documentos.index')->with('success', 'Documento subido correctamente.');
    }

    public function show($id)
    {
        $documento = Documento::where('id_documento', $id)->firstOrFail();

        $versiones = Documento::where('titulo', $documento->titulo)
                              ->orderBy('fecha_subida', 'desc')
                              ->get();

        return view('documentos.mostrar', compact('documento', 'versiones'));
    }

    public function edit($id)
    {
        $documento = Documento::where('id_documento', $id)->firstOrFail();
        return view('documentos.editar', compact('documento'));
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

        return redirect()->route('documentos.index')->with('success', 'Documento actualizado correctamente.');
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
}
