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
    // Redirigir al primer restaurante activo
    $restaurant = \App\Models\Restaurant::where('is_active', true)
        ->orderBy('created_at', 'asc')
        ->first();
    
    if ($restaurant) {
        // Si APP_DOMAIN está configurado, usar subdominio
        if (config('app.domain')) {
            return redirect()->away(
                (request()->secure() ? 'https://' : 'http://') . 
                $restaurant->slug . '.' . config('app.domain')
            );
        }
        
        // Si no, usar slug en URL
        return redirect()->route('public.menu.index', ['restaurant' => $restaurant->slug]);
    }
    
    return view('welcome');
});
