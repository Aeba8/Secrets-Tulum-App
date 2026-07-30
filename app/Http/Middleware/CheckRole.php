<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Verificar si el usuario ha iniciado sesión
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // 2. Obtener el usuario autenticado
        $user = auth()->user();

        // 3. "Los Meros Meros" tienen acceso absoluto por bypass técnico
        if ($user->Rol === 'SuperAdmin') { // <-- Validando tu columna 'Rol'
            return $next($request);
        }

        // 4. Verificar si el rol del usuario coincide con los permitidos para la pantalla
        if (in_array($user->Rol, $roles)) {
            return $next($request);
        }

        // Si el usuario está autenticado pero su rol no es válido, cerrar sesión y redirigir
        if (!$user->Rol || $user->Rol === '') {
            auth()->logout();
            session()->invalidate();
            session()->regenerateToken();
            return redirect()->route('login')->with('error', 'Tu sesión expiró. Inicia sesión nuevamente.');
        }

        // Si intenta entrar a una sección no permitida (ej: Operativo entrando a métricas)
        abort(403, 'No tienes permisos para acceder a esta sección de SecretsPad.');
    }
}