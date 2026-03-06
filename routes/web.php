<?php

use App\Http\Controllers\Public\MenuController;
use App\Http\Middleware\IdentifyPublicTenant;
use Illuminate\Support\Facades\Route;

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
    
    // Si hay APP_DOMAIN configurado y NO es el dominio base, significa que es un subdominio
    if ($baseDomain && $host !== $baseDomain && $host !== 'www.' . $baseDomain) {
        // Es un subdominio, debe ser manejado por las rutas del restaurante
        // Si llegamos aquí, el middleware ValidateSubdomain ya validó que existe
        // Extraer el slug del subdominio
        $subdomain = str_replace('.' . $baseDomain, '', $host);
        
        $restaurant = \App\Models\Restaurant::where('slug', $subdomain)
            ->where('is_active', true)
            ->first();
        
        if ($restaurant) {
            // Pasar al controlador en lugar de redirigir
            return app(\App\Http\Controllers\Public\MenuController::class)->index(request()->merge(['current_restaurant' => $restaurant]));
        }
    }
    
    // Es el dominio base, redirigir al primer restaurante
    $restaurant = \App\Models\Restaurant::where('is_active', true)
        ->orderBy('created_at', 'asc')
        ->first();
    
    if ($restaurant) {
        // Si APP_DOMAIN está configurado, usar subdominio
        if ($baseDomain) {
            return redirect()->away(
                (request()->secure() ? 'https://' : 'http://') . 
                $restaurant->slug . '.' . $baseDomain
            );
        }
        
        // Si no, usar slug en URL
        return redirect()->route('public.menu.index', ['restaurant' => $restaurant->slug]);
    }
    
    return view('welcome');
});
