<?php

namespace App\Http\Middleware;

use App\Models\Restaurant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IdentifyPublicTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Intentar obtener el tenant del subdominio primero
        $subdomain = $request->route('tenant');
        
        // Si no hay subdominio, intentar obtenerlo del slug en la URL (desarrollo local)
        if (!$subdomain) {
            $subdomain = $request->route('restaurant');
        }
        
        if (!$subdomain) {
            abort(404, 'Restaurant not found');
        }

        $restaurant = Restaurant::where('slug', $subdomain)
            ->where('is_active', true)
            ->first();

        if (!$restaurant) {
            abort(404, 'Restaurant not found');
        }

        // Compartir el restaurante con todas las vistas
        view()->share('restaurant', $restaurant);
        
        // Guardar en el request para acceso en controladores
        $request->merge(['current_restaurant' => $restaurant]);

        return $next($request);
    }
}
