@extends('public.layout')

@section('title', $product->name)

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-50 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('public.menu.index', ['restaurant' => $currentRestaurant->slug]) }}" 
                       class="text-gray-500 hover:text-amber-600 transition">
                        Menú
                    </a>
                </li>
                @if($product->dishTypes->first())
                    <li class="text-gray-400">/</li>
                    <li>
                        <a href="{{ route('public.menu.category', ['restaurant' => $currentRestaurant->slug, 'category' => $product->dishTypes->first()->id]) }}" 
                           class="text-gray-500 hover:text-amber-600 transition">
                            {{ $product->dishTypes->first()->name }}
                        </a>
                    </li>
                @endif
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">{{ $product->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Product Detail -->
<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Image Gallery -->
        @if($product->photo1 || $product->photo2 || $product->photo3)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6">
                @if($product->photo1)
                    <div class="md:col-span-2">
                        <img src="{{ asset('storage/' . $product->photo1) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-96 object-cover rounded-lg">
                    </div>
                @endif
                @if($product->photo2)
                    <img src="{{ asset('storage/' . $product->photo2) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-48 object-cover rounded-lg">
                @endif
                @if($product->photo3)
                    <img src="{{ asset('storage/' . $product->photo3) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-48 object-cover rounded-lg">
                @endif
            </div>
        @endif

        <!-- Product Info -->
        <div class="p-8">
            <div class="flex items-start justify-between mb-6">
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    <div class="flex items-center gap-3">
                        @if($product->is_new)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                ✨ Nuevo
                            </span>
                        @endif
                        @if($product->is_featured)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                                ⭐ Destacado
                            </span>
                        @endif
                    </div>
                </div>
                <div class="text-4xl font-bold text-amber-600">
                    {{ number_format($product->price, 2) }}€
                </div>
            </div>

            @if($product->description)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Descripción</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                </div>
            @endif

            @if($product->ingredients)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-2">Ingredientes</h2>
                    <p class="text-gray-600 italic">{{ $product->ingredients }}</p>
                </div>
            @endif

            <!-- Variants -->
            @if($product->variants->count() > 0)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">Variantes disponibles</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($product->variants as $variant)
                            <div class="border-2 border-amber-200 rounded-lg p-4 hover:border-amber-400 transition">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="font-semibold text-gray-900">{{ $variant->name }}</h3>
                                        @if($variant->description)
                                            <p class="text-sm text-gray-600">{{ $variant->description }}</p>
                                        @endif
                                    </div>
                                    <div class="text-xl font-bold text-amber-600">
                                        {{ number_format($variant->price, 2) }}€
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Allergens -->
            @if($product->allergens->count() > 0)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">Alérgenos</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->allergens as $allergen)
                            <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-red-100 text-red-800 border border-red-200">
                                {{ $allergen->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Meta Information -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 py-6 border-t border-gray-200">
                @if($product->preparation_time)
                    <div class="text-center">
                        <svg class="w-8 h-8 mx-auto mb-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-gray-600">Tiempo</p>
                        <p class="font-semibold text-gray-900">{{ $product->preparation_time }} min</p>
                    </div>
                @endif

                @if($product->kcal)
                    <div class="text-center">
                        <svg class="w-8 h-8 mx-auto mb-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        <p class="text-sm text-gray-600">Calorías</p>
                        <p class="font-semibold text-gray-900">{{ $product->kcal }} kcal</p>
                    </div>
                @endif

                @if($product->is_spicy)
                    <div class="text-center">
                        <span class="text-3xl mb-2 block">🌶️</span>
                        <p class="text-sm text-gray-600">Picante</p>
                        <p class="font-semibold text-gray-900">Sí</p>
                    </div>
                @endif

                <div class="text-center">
                    <svg class="w-8 h-8 mx-auto mb-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-gray-600">Estado</p>
                    <p class="font-semibold text-gray-900">Disponible</p>
                </div>
            </div>

            <!-- Categories -->
            @if($product->dishTypes->count() > 0)
                <div class="pt-6 border-t border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">Categorías</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->dishTypes as $dishType)
                            <a href="{{ route('public.menu.category', ['restaurant' => $currentRestaurant->slug, 'category' => $dishType->id]) }}" 
                               class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-amber-100 text-amber-800 hover:bg-amber-200 transition">
                                {{ $dishType->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Related Products -->
@if($relatedProducts->count() > 0)
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">También te puede gustar</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($relatedProducts as $related)
            <a href="{{ route('public.menu.product', ['restaurant' => $currentRestaurant->slug, 'product' => $related->id]) }}" 
               class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition group">
                @if($related->photo1)
                    <img src="{{ asset('storage/' . $related->photo1) }}" 
                         alt="{{ $related->name }}" 
                         class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                @endif
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 group-hover:text-amber-600 transition mb-2">
                        {{ $related->name }}
                    </h3>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-amber-600">
                            {{ number_format($related->price, 2) }}€
                        </span>
                        @if($related->is_new)
                            <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full">
                                Nuevo
                            </span>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif
@endsection
