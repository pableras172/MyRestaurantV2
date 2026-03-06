{{-- Estilo 2: Grid con imágenes (our-menu-2) --}}
@if(count($categoriesWithProducts) > 0)
    <div class="row">
        @foreach($categoriesWithProducts as $item)
            <div class="col-12 mb-[50px]">
                <div class="menu-head text-center mb-[40px]">
                    <h4 class="title font-lobster text-[40px] font-normal text-primary">{{ $item['category']->name }}</h4>
                    @if($item['category']->description)
                        <p class="text-[16px] text-bodycolor mt-3">{{ $item['category']->description }}</p>
                    @endif
                </div>
                
                <div class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 gap-[30px]">
                    @foreach($item['products'] as $product)
                    <div class="dz-card style-3 relative overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-500">
                        @if($product->photo)
                            <div class="dz-media relative h-[250px] overflow-hidden">
                                <img src="{{ asset('storage/' . $product->photo) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                                <div class="price-tag absolute top-4 right-4 bg-primary text-white px-4 py-2 rounded-lg font-bold text-lg">
                                    {{ number_format($product->price, 2) }}€
                                </div>
                            </div>
                        @else
                            <div class="dz-media relative h-[250px] overflow-hidden bg-gray-200 flex items-center justify-center">
                                <i class="flaticon-dish text-[80px] text-gray-400"></i>
                                <div class="price-tag absolute top-4 right-4 bg-primary text-white px-4 py-2 rounded-lg font-bold text-lg">
                                    {{ number_format($product->price, 2) }}€
                                </div>
                            </div>
                        @endif
                        
                        <div class="dz-content p-6 bg-white">
                            <h5 class="text-xl font-semibold mb-2">
                                <a href="{{ route('public.menu.product', ['restaurant' => $currentRestaurant->slug, 'product' => $product->id]) }}" 
                                   class="text-black2 hover:text-primary transition-colors duration-300">
                                    {{ $product->name }}
                                </a>
                            </h5>
                            
                            @if($product->description)
                            <p class="text-[15px] text-bodycolor mb-3 line-clamp-2">
                                {{ $product->description }}
                            </p>
                            @endif
                            
                            <!-- Variants -->
                            @if($product->variants->count() > 0)
                            <div class="mb-3 text-sm">
                                <span class="font-medium text-gray-700">Tamaños:</span>
                                @foreach($product->variants as $variant)
                                    <span class="ml-2 text-gray-600">{{ $variant->name }} ({{ number_format($variant->price, 2) }}€)</span>@if(!$loop->last),@endif
                                @endforeach
                            </div>
                            @endif
                            
                            <!-- Allergens -->
                            @if($product->allergens->count() > 0)
                            <div class="flex flex-wrap gap-2 items-center mt-3 pt-3 border-t">
                                @foreach($product->allergens as $allergen)
                                    @if($allergen->photo)
                                        <img src="{{ asset('storage/' . $allergen->photo) }}" 
                                             alt="{{ $allergen->name }}" 
                                             class="w-6 h-6 rounded-full object-cover"
                                             title="{{ $allergen->name }}">
                                    @else
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs bg-red-100 text-red-800">
                                            {{ $allergen->name }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                            @endif
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
