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
        \Log::info('ValidateSubdomain - Idioma -> ' . app()->getLocale());
        \Log::info('ValidateSubdomain - Inicio', [
            'host' => $request->getHost(),
            'baseDomain' => $baseDomain,
            'url' => $request->fullUrl(),
            'path' => $request->path()
        ]);        
       
        // Si no hay dominio configurado, continuar (modo desarrollo local)
        if (!$baseDomain) {
            \Log::info('ValidateSubdomain - Sin dominio configurado, permitiendo');
            return $next($request);
        }
        
        $host = $request->getHost();
        
        // Si es el dominio base o www, permitir (son válidos)
        if ($host === $baseDomain || $host === 'www.' . $baseDomain) {
            \Log::info('ValidateSubdomain - Dominio base válido');
            return $next($request);
        }
        
        // Si contiene el dominio base, verificar que el subdominio exista
        if (str_contains($host, $baseDomain)) {
            $subdomain = str_replace('.' . $baseDomain, '', $host);
            
            \Log::info('ValidateSubdomain - Verificando subdominio', [
                'subdomain' => $subdomain
            ]);
            
            // Buscar restaurante con ese slug
            $exists = Restaurant::where('slug', $subdomain)
                ->where('is_active', true)
                ->exists();
            
            \Log::info('ValidateSubdomain - Resultado verificación', [
                'subdomain' => $subdomain,
                'exists' => $exists
            ]);
            
            if (!$exists) {
                \Log::warning('ValidateSubdomain - Subdominio no existe, abortando 404');
                abort(404, 'Restaurant not found');
            }
        }
        
        \Log::info('ValidateSubdomain - Permitiendo request');
        return $next($request);
    }
}
