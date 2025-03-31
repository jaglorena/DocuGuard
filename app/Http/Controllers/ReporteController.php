<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Documento;
use App\Models\Permiso;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use App\Exports\ReportesExport;

class ReporteController extends Controller
{   public function index()
    {
        if (!Auth::check()) {
            abort(401, 'No autenticado');
        }
        if (Auth::user()->rol !== 'Administrador') {
            abort(403, 'Acceso no autorizado.');
        }

        return view('reportes.index');
    }
    public function descargarDocumentosCSV()
    {
        $documentos = DB::table('documentos')->select('titulo', 'fecha_subida', 'version', 'estado')->get();

        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, ['Título', 'Fecha de Subida', 'Versión', 'Estado']);

        foreach ($documentos as $doc) {
            fputcsv($csv, [
                preg_replace('/\[grupo=.*?\]/', '', $doc->titulo),
                Carbon::parse($doc->fecha_subida)->format('d/m/Y'),
                str_pad($doc->version, 3, '0', STR_PAD_LEFT),
                ucfirst($doc->estado),
            ]);
        }

        rewind($csv);
        $contenido = stream_get_contents($csv);
        fclose($csv);

        return response($contenido)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="reporte_documentos.csv"');
    }

    public function descargarPermisosCSV()
    {
        $permisos = DB::table('permisos')
            ->join('usuarios', 'permisos.id_usuario', '=', 'usuarios.id_usuario')
            ->join('documentos', 'permisos.id_documento', '=', 'documentos.id_documento')
            ->select(
                'usuarios.nombre as usuario',
                DB::raw("regexp_replace(documentos.titulo, '\\[grupo=.*?\\]', '', 'g') as documento"),
                'permisos.nivel_acceso as permiso'
            )
            ->orderBy('usuarios.nombre')
            ->get();

            $csv = fopen('php://temp', 'r+');
            fputcsv($csv, ['Usuario', 'Documento', 'Permiso']);
            
            foreach ($permisos as $p) {
                fputcsv($csv, [
                    $p->usuario,
                    $p->documento,
                    $p->permiso
                ]);
            }
            rewind($csv);
            return response(stream_get_contents($csv))
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="reporte_permisos.csv"');
            
    }

    public function descargarActividadCSV()
    {
        $actividad = DB::table('documentos')
            ->join('usuarios', 'documentos.id_usuario_subida', '=', 'usuarios.id_usuario')
            ->select(
                DB::raw("regexp_replace(documentos.titulo, '\\[grupo=.*?\\]', '', 'g') as documento"),
                'usuarios.nombre as usuario',
                'documentos.fecha_subida',
                'documentos.updated_at as ultima_modificacion'
            )
            ->orderByDesc('documentos.updated_at')
            ->get();

        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, ['Usuario', 'Documento', 'Fecha de Subida', 'Última Modificación']);

        foreach ($actividad as $registro) {
            fputcsv($csv, [
                $registro->usuario,
                $registro->documento,
                \Carbon\Carbon::parse($registro->fecha_subida)->format('d/m/Y'),
                \Carbon\Carbon::parse($registro->ultima_modificacion)->format('d/m/Y H:i')
            ]);
        }

        rewind($csv);
        return response(stream_get_contents($csv))
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="reporte_actividad.csv"');
    }


    public function descargarDocumentosEstadoCSV()
    {
        $estados = DB::table('documentos')
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        $csv = fopen('php://temp', 'r+');
        fputcsv($csv, ['Estado', 'Cantidad']);

        foreach ($estados as $e) {
            fputcsv($csv, [ucfirst($e->estado), $e->total]);
        }

        rewind($csv);
        $contenido = stream_get_contents($csv);
        fclose($csv);

        return response($contenido)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="reporte_documentos_estado.csv"');
    }

    public function verDocumentosPDF()
    {
        $documentos = DB::table('documentos')
            ->join('usuarios', 'documentos.id_usuario_subida', '=', 'usuarios.id_usuario')
            ->select(
                DB::raw("regexp_replace(documentos.titulo, '\\[grupo=.*?\\]', '', 'g') as documento"),
                'documentos.fecha_subida',
                'documentos.version',
                'documentos.estado',
                'usuarios.nombre as subido_por'
            )
            ->orderByDesc('documentos.fecha_subida')
            ->get();

        return view('reportes.documentos_pdf', compact('documentos'));
    }


    public function verPermisosPDF()
    {
        $permisos = DB::table('permisos')
            ->join('usuarios', 'permisos.id_usuario', '=', 'usuarios.id_usuario')
            ->join('documentos', 'permisos.id_documento', '=', 'documentos.id_documento')
            ->select(
                'usuarios.nombre as usuario',
                DB::raw("regexp_replace(documentos.titulo, '\\[grupo=.*?\\]', '', 'g') as documento"),
                'permisos.nivel_acceso as permiso'
            )
            ->orderBy('usuarios.nombre')
            ->get();

        return view('reportes.permisos_pdf', compact('permisos'));
    }


    public function verActividadPDF()
    {
        $actividad = DB::table('documentos')
            ->join('usuarios', 'documentos.id_usuario_subida', '=', 'usuarios.id_usuario')
            ->select(
                DB::raw("regexp_replace(documentos.titulo, '\\[grupo=.*?\\]', '', 'g') as documento"),
                'usuarios.nombre as usuario',
                'documentos.fecha_subida',
                'documentos.updated_at as ultima_modificacion'
            )
            ->orderByDesc('documentos.updated_at')
            ->get();

        return view('reportes.actividad_pdf', compact('actividad'));
    }

    public function verEstadoPDF()
    {
        $estados = DB::table('documentos')
            ->select('estado', DB::raw('count(*) as total'))
            ->groupBy('estado')
            ->get();

        return view('reportes.estado_pdf', compact('estados'));
    }
}
