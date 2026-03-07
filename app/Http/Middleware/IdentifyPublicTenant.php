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
        $subdomain = null;

        // 1. Intentar obtener del subdominio (bellaitalia.myrestaurant.pro)
        $host = $request->getHost();
        $baseDomain = config('app.domain');

        if ($baseDomain && str_contains($host, $baseDomain)) {
            // Extraer subdominio: bellaitalia.myrestaurant.pro → bellaitalia
            $subdomain = str_replace('.' . $baseDomain, '', $host);

            // Si es el dominio base sin subdominio, no buscar restaurante
            if ($subdomain === $baseDomain || $subdomain === 'www') {
                return $next($request);
            }
        }

        // 2. Si no hay subdominio, intentar obtener del slug en la URL (/bellaitalia)
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
            abort(404, 'Restaurant not found: ' . $subdomain);
        }

        // Compartir el restaurante con todas las vistas
        view()->share('restaurant', $restaurant);

        // Establecer el idioma de la aplicación
        // Prioridad: 1. Sesión del usuario, 2. Default del restaurante, 3. Config de la app
        $locale = session('locale') ?? $restaurant->default_language ?? config('app.locale');
        \Log::info('Identificando tenant 1', [
            'subdomain' => $subdomain,
            'restaurant_id' => $restaurant->id,
            'locale' => $locale,
        ]);

        // Validar que el idioma sea uno de los soportados
        if (!in_array($locale, ['es', 'ca', 'en'])) {
            $locale = config('app.locale');
        }
        \Log::info('Identificando tenant 2', [
            'subdomain' => $subdomain,
            'restaurant_id' => $restaurant->id,
            'locale' => $locale,
        ]);
        app()->setLocale($locale);
        \Log::info('Identificando tenant 3', [
            'subdomain' => $subdomain,
            'restaurant_id' => $restaurant->id,
            'locale' => app()->getLocale(),
        ]);
        // Guardar en el request para acceso en controladores
        $request->merge(['current_restaurant' => $restaurant]);

        return $next($request);
    }
}
