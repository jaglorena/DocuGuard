<?php
namespace App\Http\Middleware;

use App\Enums\Rol;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RolMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $usuarioAutenticado = Auth::user();
        $usuarioAEditar     = $request->route('usuario');
        \Log::info("datos recibidos!");
        \Log::info($usuarioAEditar);
        \Log::info("usuario autenticado");
        \Log::info($usuarioAutenticado->id_usuario);
        \Log::info("Resultado de la validacion::");
        \Log::info($usuarioAEditar == $usuarioAutenticado->id_usuario);

        if (auth()->check() && auth()->user()->rol === Rol::ADMINISTRADOR->value) {
            return $next($request);
        }

        if ($usuarioAEditar == $usuarioAutenticado->id_usuario) {
            return $next($request);
        } else {
            \Log::info("Paso al else");
        }

        abort(403, 'Acceso no autorizado');

    }
}
