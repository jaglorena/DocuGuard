<?php
namespace App\Http\Middleware;

use App\Enums\Rol;
use App\Models\Documento;
use App\Models\Permiso;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DocumentosMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $usuario     = Auth::user();
        $idDocumento = $request->route('id');
        $documento   = Documento::find($idDocumento);

        if (! $documento) {
            abort(404, 'Documento no encontrado.');
        }

        if ($usuario->rol === Rol::ADMINISTRADOR->value) {
            return $next($request);
        }

        if ($documento->id_usuario_subida === $usuario->id_usuario) {
            return $next($request);
        }

        $tienePermiso = Permiso::where('id_usuario', $usuario->id_usuario)
            ->where('id_documento', $documento->id_documento)
            ->exists();

        if ($tienePermiso) {
            return $next($request);
        }

        abort(403, 'No tienes permisos para acceder a este documento.');

    }
}
