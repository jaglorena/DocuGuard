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
            'documentosPorEstado' => Documento::select('estado', DB::raw('count(*) as total'))->groupBy('estado')->get(),
            'permisosPorUsuario'  => Permiso::select('nivel_acceso', DB::raw('count(*) as total'))->groupBy('nivel_acceso')->get(),
            'documentosPorFecha'  => Documento::select(
                DB::raw('TO_CHAR(fecha_subida, \'YYYY-MM\') as mes'),
                DB::raw('count(*) as total')
            )->groupBy('mes')->orderBy('mes')->get(),
            'actividadUsuarios'   => Usuario::select('nombre', 'correo', 'rol')->get(), // Asumiendo columnas adicionales
        ]);
    }
}
