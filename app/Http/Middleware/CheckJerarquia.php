<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckJerarquia
{
    public function handle(Request $request, Closure $next, $funcion)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        switch ($funcion) {
            case 'administrar':
                if (!$user->puedeAdministrar()) {
                    abort(403, 'No tienes permisos de administración.');
                }
                break;
            case 'crear_formularios':
                if (!$user->puedeCrearFormularios()) {
                    abort(403, 'No tienes permisos para crear formularios.');
                }
                break;
            case 'evaluar':
                if (!$user->puedeEvaluar()) {
                    abort(403, 'No tienes permisos para evaluar.');
                }
                break;
        }

        return $next($request);
    }
}
