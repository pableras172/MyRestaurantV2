{{-- Estilo 5: Grid animado con efectos (our-menu-5) --}}
@if(count($categoriesWithProducts) > 0)
    <div class="row">
        @foreach($categoriesWithProducts as $categoryIndex => $item)
            <div class="col-12 mb-[70px]">
                <div class="menu-head text-center mb-[45px] animate-fade-in-up" style="animation-delay: {{ $categoryIndex * 0.1 }}s">
                    <h4 class="title font-lobster text-[42px] font-normal text-primary mb-3">{{ $item['category']->name }}</h4>
                    @if($item['category']->description)
                        <p class="text-[16px] text-bodycolor max-w-3xl mx-auto">{{ $item['category']->description }}</p>
                    @endif
                </div>
                
                <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-[30px]">
                    @foreach($item['products'] as $productIndex => $product)
                    <div class="dz-card style-6 relative overflow-hidden rounded-xl bg-white shadow-lg transform hover:-translate-y-2 transition-all duration-500 animate-fade-in-up group" 
                         style="animation-delay: {{ ($categoryIndex * 0.1) + ($productIndex * 0.05) }}s">
                        
                        @if($product->photo)
                            <div class="dz-media relative h-[280px] overflow-hidden">
                                <img src="{{ asset('storage/' . $product->photo) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-125 group-hover:rotate-3 transition-all duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent opacity-80 group-hover:opacity-60 transition-opacity duration-500"></div>
                                
                                <!-- Overlay Info -->
                                <div class="absolute inset-0 flex flex-col justify-end p-5 text-white transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                    <h5 class="text-2xl font-bold mb-2">{{ $product->name }}</h5>
                                    <p class="text-sm opacity-0 group-hover:opacity-100 transition-all duration-500 delay-100 line-clamp-2">
                                        {{ $product->description }}
                                    </p>
                                </div>
                                
                                <!-- Price Badge -->
                                <div class="absolute top-4 right-4 bg-primary text-white px-4 py-2 rounded-full font-bold text-lg shadow-xl transform group-hover:scale-110 transition-transform duration-300">
                                    {{ number_format($product->price, 2) }}€
                                </div>
                                
                                <!-- Featured Badge -->
                                @if($product->is_highlighted)
                                <div class="absolute top-4 left-4 bg-yellow-400 text-black px-3 py-1.5 rounded-full text-xs font-bold shadow-lg animate-pulse">
                                    ⭐ Destacado
                                </div>
                                @endif
                            </div>
                        @else
                            <div class="dz-media relative h-[280px] overflow-hidden bg-gradient-to-br from-primary/10 to-primary/30 flex items-center justify-center group-hover:from-primary/20 group-hover:to-primary/40 transition-all duration-500">
                                <i class="flaticon-dish text-[100px] text-primary/30 group-hover:scale-110 transition-transform duration-500"></i>
                                <div class="absolute inset-0 flex flex-col justify-end p-5">
                                    <h5 class="text-2xl font-bold mb-2 text-gray-800">{{ $product->name }}</h5>
                                    <p class="text-sm text-gray-600 line-clamp-2">{{ $product->description }}</p>
                                </div>
                                <div class="absolute top-4 right-4 bg-primary text-white px-4 py-2 rounded-full font-bold text-lg shadow-xl">
                                    {{ number_format($product->price, 2) }}€
                                </div>
                            </div>
                        @endif
                        
                        <div class="dz-content p-5">
                            <!-- Meta Info with Icons -->
                            @if($product->preparation_time || $product->kcal || $product->is_spicy)
                            <div class="flex flex-wrap gap-2 mb-4">
                                @if($product->preparation_time)
                                    <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                        <i class="flaticon-clock mr-1.5"></i>{{ $product->preparation_time }} min
                                    </span>
                                @endif
                                @if($product->kcal)
                                    <span class="inline-flex items-center px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-medium">
                                        <i class="flaticon-fire mr-1.5"></i>{{ $product->kcal }} kcal
                                    </span>
                                @endif
                                @if($product->is_spicy)
                                    <span class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium animate-bounce">
                                        🌶️ Picante
                                    </span>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Variants -->
                            @if($product->variants->count() > 0)
                            <div class="mb-4 pb-4 border-b">
                                <div class="text-xs font-semibold text-gray-700 mb-2">Tamaños disponibles:</div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($product->variants as $variant)
                                        <span class="inline-block px-3 py-1.5 bg-gray-100 hover:bg-primary hover:text-white rounded-lg text-xs transition-colors duration-300 cursor-pointer">
                                            {{ $variant->name }} - {{ number_format($variant->price, 2) }}€
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            <!-- Allergens -->
                            @if($product->allergens->count() > 0)
                            <div class="flex flex-wrap gap-2 items-center mb-4">
                                <span class="text-xs font-semibold text-gray-700">Alérgenos:</span>
                                @foreach($product->allergens as $allergen)
                                    @if($allergen->photo)
                                        <img src="{{ asset('storage/' . $allergen->photo) }}" 
                                             alt="{{ $allergen->name }}" 
                                             class="w-8 h-8 rounded-full object-cover border-2 border-red-200 hover:scale-125 transition-transform duration-300"
                                             title="{{ $allergen->name }}">
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-red-100 text-red-800 hover:bg-red-200 transition-colors">
                                            {{ $allergen->name }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                            
                            <!-- Action Button -->
                            <a href="{{ route('public.menu.product', ['restaurant' => $currentRestaurant->slug, 'product' => $product->id]) }}" 
                               class="block text-center bg-primary text-white py-3 rounded-lg font-semibold hover:bg-primary/90 transform hover:scale-105 transition-all duration-300 shadow-md hover:shadow-xl">
                                Ver detalles completos
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
@else
    <!-- Empty State -->
    <div class="text-center py-[100px] animate-fade-in">
        <div class="inline-block mb-5">
            <i class="flaticon-restaurant text-[80px] text-gray-300 animate-bounce"></i>
        </div>
        <h3 class="font-lobster text-3xl text-gray-700 mb-4">No hay menú disponible</h3>
        <p class="text-gray-600 mb-8">Estamos preparando nuestro menú. Vuelve pronto.</p>
    </div>
@endif

<style>
@keyframes fade-in-up {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fade-in-up {
    animation: fade-in-up 0.6s ease-out both;
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out both;
}
</style>
