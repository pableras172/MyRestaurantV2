{{-- Estilo 3: Grid destacado con precios y badges (our-menu-3) --}}
@if(count($categoriesWithProducts) > 0)
    <div class="row">
        @foreach($categoriesWithProducts as $item)
            <div class="col-12 mb-[60px]">
                <div class="menu-head text-center mb-[50px]">
                    <h4 class="title font-lobster text-[45px] font-normal text-primary mb-4">{{ $item['category']->name }}</h4>
                    @if($item['category']->description)
                        <p class="text-[17px] text-bodycolor max-w-2xl mx-auto">{{ $item['category']->description }}</p>
                    @endif
                </div>
                
                <div class="grid lg:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-[25px]">
                    @foreach($item['products'] as $product)
                    <div class="dz-card style-5 relative overflow-hidden rounded-2xl bg-white shadow-md hover:shadow-xl transition-all duration-300 group">
                        <!-- Featured Badge -->
                        @if($product->is_highlighted)
                        <div class="absolute top-3 left-3 bg-yellow-400 text-black px-3 py-1 rounded-full text-xs font-bold z-10">
                            ⭐ Destacado
                        </div>
                        @endif
                        
                        @if($product->photo)
                            <div class="dz-media relative h-[200px] overflow-hidden">
                                <img src="{{ asset('storage/' . $product->photo) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute bottom-3 left-3 right-3 text-white">
                                    <h5 class="text-lg font-bold mb-1">{{ $product->name }}</h5>
                                    <div class="text-2xl font-bold text-yellow-400">{{ number_format($product->price, 2) }}€</div>
                                </div>
                            </div>
                        @else
                            <div class="dz-media relative h-[200px] overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <i class="flaticon-dish text-[60px] text-gray-400"></i>
                                <div class="absolute bottom-3 left-3 right-3">
                                    <h5 class="text-lg font-bold mb-1 text-gray-800">{{ $product->name }}</h5>
                                    <div class="text-2xl font-bold text-primary">{{ number_format($product->price, 2) }}€</div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="dz-content p-4">
                            @if($product->description)
                            <p class="text-sm text-bodycolor mb-3 line-clamp-3">
                                {{ $product->description }}
                            </p>
                            @endif
                            
                            <!-- Meta Info -->
                            <div class="flex flex-wrap gap-2 mb-3 text-xs">
                                @if($product->preparation_time)
                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                        <i class="flaticon-clock mr-1"></i>{{ $product->preparation_time }}'
                                    </span>
                                @endif
                                @if($product->kcal)
                                    <span class="inline-flex items-center px-2 py-1 bg-orange-100 text-orange-800 rounded">
                                        <i class="flaticon-fire mr-1"></i>{{ $product->kcal }} kcal
                                    </span>
                                @endif
                                @if($product->is_spicy)
                                    <span class="inline-flex items-center px-2 py-1 bg-red-100 text-red-800 rounded">
                                        🌶️ Picante
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Variants -->
                            @if($product->variants->count() > 0)
                            <div class="mb-3 pb-3 border-b text-xs">
                                <div class="font-medium text-gray-700 mb-1">Tamaños disponibles:</div>
                                @foreach($product->variants as $variant)
                                    <span class="inline-block mr-2 mb-1 px-2 py-1 bg-gray-100 rounded">
                                        {{ $variant->name }} - {{ number_format($variant->price, 2) }}€
                                    </span>
                                @endforeach
                            </div>
                            @endif
                            
                            <!-- Allergens -->
                            @if($product->allergens->count() > 0)
                            <div class="flex flex-wrap gap-1.5 items-center">
                                @foreach($product->allergens as $allergen)
                                    @if($allergen->photo)
                                        <img src="{{ asset('storage/' . $allergen->photo) }}" 
                                             alt="{{ $allergen->name }}" 
                                             class="w-7 h-7 rounded-full object-cover border border-red-300"
                                             title="{{ $allergen->name }}">
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs bg-red-100 text-red-800">
                                            {{ $allergen->name }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                            
                            <!-- Button -->
                            <a href="{{ route('public.menu.product', ['restaurant' => $currentRestaurant->slug, 'product' => $product->id]) }}" 
                               class="mt-4 block text-center bg-primary text-white py-2 rounded-lg hover:bg-primary/90 transition-colors duration-300">
                                Ver detalles
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
    <div class="text-center py-[100px]">
        <div class="inline-block mb-5">
            <i class="flaticon-restaurant text-[80px] text-gray-300"></i>
        </div>
        <h3 class="font-lobster text-3xl text-gray-700 mb-4">No hay menú disponible</h3>
        <p class="text-gray-600 mb-8">Estamos preparando nuestro menú. Vuelve pronto.</p>
    </div>
@endif
