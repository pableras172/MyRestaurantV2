<?php

use App\Http\Controllers\Public\MenuController;
use App\Http\Middleware\IdentifyPublicTenant;
use Illuminate\Support\Facades\Route;

// Ruta para cambiar idioma (sin middleware, disponible globalmente)
Route::get('/change-language', function () {

    $locale = request()->input('locale', 'es');
    $redirect = request()->input('redirect', url('/'));

    // Validar que el idioma sea uno de los soportados
    if (in_array($locale, ['es', 'ca', 'en'])) {
        \Log::info('CWEB -> ambio de idioma', [
            'locale' => $locale,
            'redirect' => $redirect
        ]);
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }

    return redirect($redirect);
})->name('public.language.change');

// Rutas de desarrollo local (con slug en la URL)
Route::prefix('{restaurant:slug}')->middleware(IdentifyPublicTenant::class)->group(function () {
    Route::get('/', [MenuController::class, 'index'])->name('public.menu.index');
    Route::get('/menu', [MenuController::class, 'index']);
    Route::get('/category/{category}', [MenuController::class, 'category'])->name('public.menu.category');
    Route::get('/product/{product}', [MenuController::class, 'product'])->name('public.menu.product');
});

// Página de bienvenida para el dominio principal
Route::get('/', function () {
    $baseDomain = config('app.domain');
    $host = request()->getHost();
    $locale = request()->input('locale', 'es');
    if (in_array($locale, ['es', 'ca', 'en'])) {
        \Log::info('CWEB -> ambio de idioma', [
            'locale' => $locale,
            'redirect' => $redirect
        ]);
        session(['locale' => $locale]);
        app()->setLocale($locale);
    }

    \Log::info('Ruta / - Inicio', [
        'host' => $host,
        'baseDomain' => $baseDomain
    ]);

    // Si hay APP_DOMAIN configurado y NO es el dominio base, significa que es un subdominio
    if ($baseDomain && $host !== $baseDomain && $host !== 'www.' . $baseDomain) {
        // Es un subdominio, debe ser manejado por las rutas del restaurante
        // Si llegamos aquí, el middleware ValidateSubdomain ya validó que existe
        // Extraer el slug del subdominio
        $subdomain = str_replace('.' . $baseDomain, '', $host);

        \Log::info('Ruta / - Es subdominio', [
            'subdomain' => $subdomain
        ]);

        $restaurant = \App\Models\Restaurant::where('slug', $subdomain)
            ->where('is_active', true)
            ->first();

        if ($restaurant) {
            \Log::info('Ruta / - Restaurante encontrado, mostrando menú');
            // Compartir el restaurante con las vistas
            view()->share('restaurant', $restaurant);
            // Pasar al controlador con ambos parámetros requeridos
            return app(\App\Http\Controllers\Public\MenuController::class)
                ->index(request()->merge(['current_restaurant' => $restaurant]), $subdomain);
        }
    }

    \Log::info('Ruta / - Es dominio base, redirigiendo a primer restaurante');

    // Es el dominio base, redirigir al primer restaurante
    $restaurant = \App\Models\Restaurant::where('is_active', true)
        ->orderBy('created_at', 'asc')
        ->first();

    if ($restaurant) {
        // Si APP_DOMAIN está configurado, usar subdominio
        if ($baseDomain) {
            $redirectUrl = (request()->secure() ? 'https://' : 'http://') .
                $restaurant->slug . '.' . $baseDomain;
            \Log::info('Ruta / - Redirigiendo a', ['url' => $redirectUrl]);
            return redirect()->away($redirectUrl);
        }

        // Si no, usar slug en URL
        return redirect()->route('public.menu.index', ['restaurant' => $restaurant->slug]);
    }

    return view('welcome');
});
