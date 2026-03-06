{{-- Estilo 4: Grid compacto (our-menu-4) --}}
@if(count($categoriesWithProducts) > 0)
    @foreach($categoriesWithProducts as $item)
        <div class="mb-[40px]">
            <div class="menu-head mb-[30px] pb-3 border-b-2 border-primary/20">
                <h4 class="title font-lobster text-[32px] font-normal text-primary inline-block">{{ $item['category']->name }}</h4>
                @if($item['category']->description)
                    <p class="text-[14px] text-bodycolor mt-1">{{ $item['category']->description }}</p>
                @endif
            </div>
            
            <div class="grid xl:grid-cols-6 lg:grid-cols-5 md:grid-cols-4 sm:grid-cols-3 grid-cols-2 gap-[15px]">
                @foreach($item['products'] as $product)
                <div class="dz-card style-4 relative overflow-hidden rounded-lg border border-gray-200 hover:border-primary hover:shadow-lg transition-all duration-300 group">
                    @if($product->photo)
                        <div class="dz-media relative h-[120px] overflow-hidden">
                            <img src="{{ asset('storage/' . $product->photo) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition-colors"></div>
                        </div>
                    @else
                        <div class="dz-media relative h-[120px] overflow-hidden bg-gray-100 flex items-center justify-center">
                            <i class="flaticon-dish text-[40px] text-gray-300"></i>
                        </div>
                    @endif
                    
                    <div class="dz-content p-3 bg-white">
                        <h6 class="text-sm font-semibold mb-1 line-clamp-2 min-h-[40px]">
                            <a href="{{ route('public.menu.product', ['restaurant' => $currentRestaurant->slug, 'product' => $product->id]) }}" 
                               class="text-black2 hover:text-primary transition-colors">
                                {{ $product->name }}
                            </a>
                        </h6>
                        
                        <div class="text-primary font-bold text-base mb-2">{{ number_format($product->price, 2) }}€</div>
                        
                        <!-- Allergens mini -->
                        @if($product->allergens->count() > 0)
                        <div class="flex flex-wrap gap-1">
                            @foreach($product->allergens->take(3) as $allergen)
                                @if($allergen->photo)
                                    <img src="{{ asset('storage/' . $allergen->photo) }}" 
                                         alt="{{ $allergen->name }}" 
                                         class="w-5 h-5 rounded-full object-cover"
                                         title="{{ $allergen->name }}">
                                @endif
                            @endforeach
                            @if($product->allergens->count() > 3)
                                <span class="text-xs text-gray-500">+{{ $product->allergens->count() - 3 }}</span>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endforeach
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
