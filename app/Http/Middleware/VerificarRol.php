<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificarRol
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $usuario = $request->user();
        if (!$usuario || $usuario->Estatus !== 'Activo') {
            abort(403, 'La cuenta está inactiva o no tiene autorización.');
        }
        $rol = $usuario->rol?->Nombre;
        if (!$rol || !in_array($rol, $roles, true)) {
            abort(403, 'Tu rol no tiene permiso para realizar esta acción.');
        }
        return $next($request);
    }
}
