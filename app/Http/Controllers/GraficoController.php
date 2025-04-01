<?php
namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Permiso;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class GraficoController extends Controller
{
    public function index()
    {
        return view('Graficos.index');
    }

    public function data()
    {
        return response()->json([
            'documentosPorEstado' => Documento::select('estado', DB::raw('count(*) as total'))
                ->groupBy('estado')->get(),

            'permisosPorUsuario' => Permiso::select('nivel_acceso', DB::raw('count(*) as total'))
                ->groupBy('nivel_acceso')->get(),

            'documentosPorFecha' => Documento::select(
                DB::raw("TO_CHAR(fecha_subida, 'YYYY-MM') as periodo"),
                DB::raw('count(*) as total')
            )->groupBy('periodo')->orderBy('periodo')->get(),
            'documentosPorTrimestre' => Documento::select(
                DB::raw("EXTRACT(YEAR FROM fecha_subida) || ' - T' || EXTRACT(QUARTER FROM fecha_subida) as periodo"),
                DB::raw('count(*) as total')
            )->groupBy('periodo')->orderBy('periodo')->get(),
            'actividadUsuarios' => Usuario::withCount([
                'permisos as visualizaciones' => function ($q) {
                    $q->where('nivel_acceso', 'lectura');
                },
                'permisos as ediciones' => function ($q) {
                    $q->where('nivel_acceso', 'escritura');
                }
            ])->get(['id_usuario', 'nombre']),
        ]);
    }
}
