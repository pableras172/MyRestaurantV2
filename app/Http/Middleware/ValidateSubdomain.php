<?php

namespace App\Http\Middleware;

use App\Models\Restaurant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSubdomain
{
    /**
     * Valida que el subdominio corresponda a un restaurante activo.
     * Se ejecuta antes de procesar cualquier ruta.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $baseDomain = config('app.domain');
        
        // Si no hay dominio configurado, continuar (modo desarrollo local)
        if (!$baseDomain) {
            return $next($request);
        }
        
        $host = $request->getHost();
        
        // Si es el dominio base o www, permitir (son válidos)
        if ($host === $baseDomain || $host === 'www.' . $baseDomain) {
            return $next($request);
        }
        
        // Si contiene el dominio base, verificar que el subdominio exista
        if (str_contains($host, $baseDomain)) {
            $subdomain = str_replace('.' . $baseDomain, '', $host);
            
            // Buscar restaurante con ese slug
            $exists = Restaurant::where('slug', $subdomain)
                ->where('is_active', true)
                ->exists();
            
            if (!$exists) {
                abort(404, 'Restaurant not found');
            }
        }
        
        return $next($request);
    }
}
