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
    return view('welcome');
});
