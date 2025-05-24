<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Adiciona cabeçalhos CORS a todas as respostas.
     */
    public function handle($request, Closure $next)
    {
        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

        // Para requisições OPTIONS (preflight), retorna apenas os headers
        if ($request->isMethod('OPTIONS')) {
            return response('', 200, $response->headers->all());
        }

        return $response;
    }
}
