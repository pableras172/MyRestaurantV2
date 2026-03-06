@extends('public.layout')

@section('title', $category->name)

@section('content')
<!-- Banner -->
<section class="bg-[url('{{ asset('template/images/banner/bnr1.jpg') }}')] bg-fixed relative z-[1] after:content-[''] after:absolute after:z-[-1] after:bg-[#222222e6] after:opacity-100 after:w-full after:h-full after:top-0 after:left-0 pt-[50px] lg:h-[450px] sm:h-[400px] h-[300px] overflow-hidden bg-cover bg-center">
    <div class="container table h-full relative z-[1] text-center">
        <div class="dz-bnr-inr-entry align-middle table-cell">
            <h2 class="font-lobster text-white mb-5 2xl:text-[70px] md:text-[60px] text-[40px] leading-[1.2]">{{ $category->name }}</h2>
            <nav aria-label="breadcrumb" class="breadcrumb-row">
                <ul class="breadcrumb bg-primary shadow-[0px_10px_20px_rgba(0,0,0,0.05)] rounded-[10px] inline-block lg:py-[13px] md:py-[10px] sm:py-[5px] py-[7px] lg:px-[30px] md:px-[18px] sm:px-5 px-3.5 m-0">
                    <li class="breadcrumb-item p-0 inline-block text-[15px] font-normal">
                        <a href="{{ route('public.menu.index', ['restaurant' => $currentRestaurant->slug]) }}" class="text-white">Inicio</a>
                    </li>
                    <li class="breadcrumb-item text-white p-0 inline-block text-[15px] font-normal active pl-2">{{ $category->name }}</li>
                </ul>
            </nav>
        </div>
    </div>
</section>
<!-- Banner End -->

<!-- Subcategories Pills -->
@if($subcategories->count() > 0)
<section class="bg-white py-5 border-b">
    <div class="container">
        <div class="flex flex-wrap gap-3 justify-center">
            <a href="{{ route('public.menu.category', ['restaurant' => $currentRestaurant->slug, 'category' => $category->id]) }}" 
               class="inline-flex items-center px-6 py-2.5 rounded-full bg-primary text-white hover:bg-primary/90 transition font-medium">
                Todos ({{ $products->count() }})
            </a>
            @foreach($subcategories as $subcategory)
                <a href="{{ route('public.menu.category', ['restaurant' => $currentRestaurant->slug, 'category' => $subcategory->id]) }}" 
                   class="inline-flex items-center px-6 py-2.5 rounded-full bg-gray-100 text-gray-700 hover:bg-primary hover:text-white transition font-medium">
                    {{ $subcategory->name }}
                    <span class="ml-2 text-xs bg-white/30 px-2 py-0.5 rounded-full">
                        {{ $subcategory->products_count }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Products Menu Style -->
<section class="lg:pt-[100px] sm:pt-[70px] pt-[50px] lg:pb-[100px] sm:pb-[70px] pb-[50px] section-wrapper-7 overflow-hidden relative bg-white">
    <div class="container">
        @if($products->count() > 0)
            @php
                // Agrupar productos en columnas de 3
                $productsChunked = $products->chunk(ceil($products->count() / 3));
            @endphp
            <div class="row inner-section-wrapper">
                @foreach($productsChunked as $columnProducts)
                <div class="xl:w-1/3 md:w-1/2 w-full px-[15px]">
                    @if($loop->first && $category->description)
                    <div class="menu-head mb-[25px]">
                        <p class="text-[15px] text-bodycolor leading-[21px]">{{ $category->description }}</p>
                    </div>
                    @endif
                    
                    @foreach($columnProducts as $product)
                    <div class="dz-shop-card style-2 relative overflow-hidden mb-[30px] p-0 shadow-none">
                        <div class="dz-content flex flex-col w-full">
                            <div class="dz-head flex justify-between items-center mb-3">
                                <span class="header-text lg:text-lg text-base lg:leading-7 font-semibold flex-shrink-0">
                                    <a href="{{ route('public.menu.product', ['restaurant' => $currentRestaurant->slug, 'product' => $product->id]) }}" 
                                       class="text-black2 max-w-[280px] text-ellipsis overflow-hidden block whitespace-nowrap duration-500 hover:text-primary">
                                        {{ $product->name }}
                                        @if($product->is_new)
                                            <span class="ml-2 text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full">Nuevo</span>
                                        @endif
                                    </a>
                                </span>
                                <span class="img-line block"></span>
                                <span class="header-price text-primary font-semibold text-xl leading-7 flex-shrink-0">{{ number_format($product->price, 2) }}€</span>
                            </div>
                            
                            @if($product->description)
                            <p class="dz-body text-[15px] text-bodycolor leading-[21px] font-normal mb-2">
                                {{ $product->description }}
                            </p>
                            @endif
                            
                            <!-- Variants -->
                            @if($product->variants->count() > 0)
                            <div class="mb-2">
                                <div class="flex flex-wrap gap-2">
                                    @foreach($product->variants as $variant)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs bg-blue-50 text-blue-700 border border-blue-200">
                                            {{ $variant->name }}: {{ number_format($variant->price, 2) }}€
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <!-- Allergens -->
                            @if($product->allergens->count() > 0)
                            <div class="flex flex-wrap gap-2 items-center mb-2">
                                @foreach($product->allergens as $allergen)
                                    @if($allergen->photo)
                                        <div class="relative group">
                                            <img src="{{ asset('storage/' . $allergen->photo) }}" 
                                                 alt="{{ $allergen->name }}" 
                                                 class="w-8 h-8 rounded-full object-cover border-2 border-red-200"
                                                 title="{{ $allergen->name }}">
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-red-100 text-red-800">
                                            {{ $allergen->name }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                            
                            <!-- Meta Info -->
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                @if($product->preparation_time)
                                    <span class="flex items-center">
                                        <i class="flaticon-clock mr-1"></i>
                                        {{ $product->preparation_time }} min
                                    </span>
                                @endif
                                @if($product->kcal)
                                    <span class="flex items-center">
                                        <i class="flaticon-fire mr-1"></i>
                                        {{ $product->kcal }} kcal
                                    </span>
                                @endif
                                @if($product->is_spicy)
                                    <span class="flex items-center text-red-600">
                                        🌶️ Picante
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-[100px]">
                <div class="inline-block mb-5">
                    <i class="flaticon-restaurant text-[80px] text-gray-300"></i>
                </div>
                <h3 class="font-lobster text-3xl text-gray-700 mb-4">No hay productos disponibles</h3>
                <p class="text-gray-600 mb-8">Esta categoría aún no tiene platos publicados.</p>
                <a href="{{ route('public.menu.index', ['restaurant' => $currentRestaurant->slug]) }}" 
                   class="btn btn-primary inline-block">
                    <span>Volver al menú</span>
                </a>
            </div>
        @endif
    </div>
    
    <!-- Background decorations -->
    <img src="{{ asset('template/images/background/pic5.png') }}" alt="" class="bg1 bottom-[10px] left-0 absolute max-2xl:hidden animate-dz">
    <img src="{{ asset('template/images/background/pic6.png') }}" alt="" class="top-[15px] right-[10px] absolute max-2xl:hidden animate-dz">
</section>

<!-- Info Banner -->
<section class="py-[50px] bg-light">
    <div class="container">
        <div class="text-center">
            <h3 class="font-lobster text-primary text-3xl mb-4">¿Tienes alguna pregunta sobre este plato?</h3>
            <p class="text-bodycolor max-w-2xl mx-auto">
                Nuestro personal estará encantado de ayudarte con cualquier consulta sobre ingredientes, 
                preparación o recomendaciones.
            </p>
            @if($currentRestaurant->phone_1)
            <a href="tel:{{ $currentRestaurant->phone_1 }}" class="btn btn-primary inline-block mt-5">
                <span><i class="flaticon-phone mr-2"></i>Llamar ahora</span>
            </a>
            @endif
        </div>
    </div>
</section>
@endsection
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
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 font-medium">{{ $category->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Category Header -->
<div class="bg-gradient-to-r from-amber-500 to-orange-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl font-bold mb-4">{{ $category->name }}</h1>
        @if($category->description)
            <p class="text-xl text-amber-50 max-w-3xl">{{ $category->description }}</p>
        @endif
    </div>
</div>

<!-- Subcategories (if any) -->
@if($subcategories->count() > 0)
<div class="bg-white border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-wrap gap-3">
            @foreach($subcategories as $subcategory)
                <a href="{{ route('public.menu.category', ['restaurant' => $currentRestaurant->slug, 'category' => $subcategory->id]) }}" 
                   class="inline-flex items-center px-4 py-2 rounded-full bg-amber-100 text-amber-800 hover:bg-amber-200 transition">
                    {{ $subcategory->name }}
                    <span class="ml-2 text-xs bg-amber-200 px-2 py-0.5 rounded-full">
                        {{ $subcategory->products_count }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Products Grid -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($products->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="flex">
                        @if($product->photo1)
                            <div class="w-48 flex-shrink-0">
                                <img src="{{ asset('storage/' . $product->photo1) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover">
                            </div>
                        @endif
                        <div class="flex-1 p-6">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <a href="{{ route('public.menu.product', ['restaurant' => $currentRestaurant->slug, 'product' => $product->id]) }}" 
                                       class="text-xl font-bold text-gray-900 hover:text-amber-600 transition">
                                        {{ $product->name }}
                                    </a>
                                    @if($product->is_new)
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Nuevo
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xl font-bold text-amber-600 ml-4">
                                    {{ number_format($product->price, 2) }}€
                                </div>
                            </div>

                            @if($product->description)
                                <p class="text-gray-600 text-sm mb-3">{{ $product->description }}</p>
                            @endif

                            @if($product->ingredients)
                                <p class="text-gray-500 text-xs mb-3 italic">{{ Str::limit($product->ingredients, 100) }}</p>
                            @endif

                            <!-- Variants -->
                            @if($product->variants->count() > 0)
                                <div class="mb-3">
                                    <p class="text-xs text-gray-500 mb-1">Variantes disponibles:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($product->variants as $variant)
                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                                {{ $variant->name }} - {{ number_format($variant->price, 2) }}€
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Allergens -->
                            @if($product->allergens->count() > 0)
                                <div class="flex flex-wrap gap-1 mb-3">
                                    @foreach($product->allergens as $allergen)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            {{ $allergen->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Meta Info -->
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                @if($product->preparation_time)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        {{ $product->preparation_time }} min
                                    </span>
                                @endif
                                @if($product->kcal)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        {{ $product->kcal }} kcal
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="mt-2 text-lg font-medium text-gray-900">No hay productos disponibles</h3>
            <p class="mt-1 text-sm text-gray-500">Esta categoría aún no tiene platos publicados.</p>
            <div class="mt-6">
                <a href="{{ route('public.menu.index', ['restaurant' => $currentRestaurant->slug]) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-amber-600 hover:bg-amber-700 transition">
                    Volver al menú
                </a>
            </div>
        </div>
    @endif
</section>
@endsection
