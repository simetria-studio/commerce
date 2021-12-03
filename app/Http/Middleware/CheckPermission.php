<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica se está logado, se não tiver redireciona
        if ( !auth()->check() ) return redirect()->route('login');

        /*
        * Verifica a permissão do usuario
        */
        // Recupera a permissão do usuário logado
        $permission = auth()->user()->permission;

        if((int)$permission !== 10) return redirect('/perfil');

        // Permite que continue (Caso não entre em nenhum dos if acima)...
        return $next($request);
    }
}
