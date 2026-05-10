<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para verificar se o usuário tem uma permissão específica
 * 
 * Uso: ->middleware('permission:people.view')
 * Verifica se o usuário autenticado tem a permissão especificada
 * Super-admins têm todas as permissões automaticamente
 */
class EnsureUserHasPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Verifica se o usuário está autenticado
        if (!auth()->check()) {
            abort(403, 'Você precisa estar autenticado para acessar esta página.');
        }

        $user = auth()->user();

        // Super-admin tem todas as permissões
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Verifica se o usuário tem a permissão específica
        if (!$user->hasPermission($permission)) {
            abort(403, 'Você não tem permissão para acessar esta página.');
        }

        return $next($request);
    }
}
