<?php

use App\Http\Controllers\Public\MenuController;
use App\Http\Middleware\IdentifyPublicTenant;
use Illuminate\Support\Facades\Route;

// Página principal (solo para dominio base sin subdominio)
Route::domain(env('APP_DOMAIN', 'localhost'))->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
});

// Rutas públicas del menú del restaurante (con subdominio)
Route::domain('{tenant}.' . env('APP_DOMAIN', 'localhost'))
    ->middleware(IdentifyPublicTenant::class)
    ->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('public.menu.index');
        Route::get('/menu', [MenuController::class, 'index']); // Alias por compatibilidad
        Route::get('/category/{category}', [MenuController::class, 'category'])->name('public.menu.category');
        Route::get('/product/{product}', [MenuController::class, 'product'])->name('public.menu.product');
    });

// Rutas de fallback para desarrollo local (con slug en la URL)
if (app()->environment('local')) {
    Route::prefix('{restaurant:slug}')->middleware(IdentifyPublicTenant::class)->group(function () {
        Route::get('/', [MenuController::class, 'index']);
        Route::get('/menu', [MenuController::class, 'index']);
        Route::get('/category/{category}', [MenuController::class, 'category']);
        Route::get('/product/{product}', [MenuController::class, 'product']);
    });
}
