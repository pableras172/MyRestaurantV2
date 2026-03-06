{{-- Estilo 1: Listado en columnas (our-menu-1) --}}
@if(count($categoriesWithProducts) > 0)
    @php
        // Dividir categorías en grupos de 3 para las columnas
        $categoriesChunked = array_chunk($categoriesWithProducts, ceil(count($categoriesWithProducts) / 3));
        
        // Obtener todas las categorías para los filtros
        $allCategories = collect($categoriesWithProducts)->pluck('category');
    @endphp
    
    <!-- Filtros de categorías -->
    
    <div class="row mb-[10px]">
        
        <div class="col-12">
            <hr>
            <div class="site-filters text-center">
                <ul class="category-filters style-1 flex flex-wrap justify-center gap-[10px]">
                    <li data-filter="*" class="btn lg:py-2 lg:px-[15px] p-2 duration-500 active cursor-pointer">
                        <a class="flex items-center lg:text-[15px] text-[13px] overflow-hidden">
                            <span class="mb-0"><i class="flaticon-fast-food text-[25px] lg:mr-[10px] mr-[5px]"></i></span>
                            TODOS
                        </a>
                    </li>
                    @foreach($allCategories as $category)
                    <li data-filter=".category-{{ $category->id }}" class="btn lg:py-2 lg:px-[15px] p-2 duration-500 cursor-pointer">
                        <a class="flex items-center lg:text-[15px] text-[13px] overflow-hidden">
                            <span class="mb-0">
                                @if($category->icon)
                                    <i class="{{ $category->icon }} text-[25px] lg:mr-[10px] mr-[5px]"></i>
                                @else
                                    <i class="flaticon-dish text-[25px] lg:mr-[10px] mr-[5px]"></i>
                                @endif
                            </span>
                            {{ strtoupper($category->name) }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    
    <div class="row inner-section-wrapper md:mb-[50px]" id="menu-items-container">
        @foreach($categoriesChunked as $columnIndex => $columnCategories)
        <div class="xl:w-1/3 md:w-1/2 w-full px-[15px]">
            @foreach($columnCategories as $item)
                <div class="menu-head mb-[25px] text-center category-{{ $item['category']->id }} menu-item">
                    @if(isset($item['parent_category']))
                        {{-- Categoría hija: mostrar padre → hija --}}
                        <h5 class="text-[18px] font-normal text-gray-600 mb-1">{{ $item['parent_category']->name }}</h5>
                        <h4 class="title text-[28px] font-normal text-primary">{{ $item['category']->name }}</h4>
                    @else
                        {{-- Categoría padre --}}
                        <h4 class="title text-[34px] font-normal text-primary">{{ $item['category']->name }}</h4>
                    @endif
                    @if($item['category']->description)
                        <p class="text-[15px] text-bodycolor mt-2">{{ $item['category']->description }}</p>
                    @endif
                </div>
                
                @foreach($item['products'] as $product)
                <div class="dz-shop-card style-2 relative overflow-hidden mb-[30px] p-0 shadow-none category-{{ $item['category']->id }} menu-item">
                    <div class="dz-content flex flex-col w-full">
                        <div class="dz-head flex justify-between items-center mb-3">
                            <span class="header-text lg:text-lg text-base lg:leading-7 font-semibold">
                                <a href="{{ route('public.menu.product', ['restaurant' => $currentRestaurant->slug, 'product' => $product->id]) }}" 
                                   class="text-black2 max-w-[280px] text-ellipsis overflow-hidden block whitespace-nowrap duration-500 hover:text-primary">
                                    {{ $product->name }}
                                </a>
                            </span>
                            <span class="img-line block"></span>
                            <span class="header-price text-primary font-semibold text-xl leading-7">{{ number_format($product->price, 2) }}€</span>
                        </div>
                        
                        @if($product->description)
                        <p class="dz-body text-[15px] text-bodycolor leading-[21px] font-normal mb-2">
                            {{ $product->description }}
                        </p>
                        @endif
                        
                        <!-- Variants -->
                        @if($product->variants->count() > 0)
                        <div class="mb-2 text-sm text-gray-600">
                            <span class="font-medium">Opciones:</span>
                            @foreach($product->variants as $variant)
                                <span class="ml-2">{{ $variant->name }} ({{ number_format($variant->price, 2) }}€)</span>@if(!$loop->last),@endif
                            @endforeach
                        </div>
                        @endif
                        
                        <!-- Allergens -->
                        @if($product->allergens->count() > 0)
                        <div class="flex flex-wrap gap-1 items-center mt-2">
                            @foreach($product->allergens as $allergen)
                                @if($allergen->photo)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $allergen->photo) }}" 
                                             alt="{{ $allergen->name }}" 
                                             class="w-6 h-6 rounded-full object-cover"
                                             title="{{ $allergen->name }}">
                                    </div>
                                @else
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] bg-gray-100 text-gray-700 border border-gray-300">
                                        {{ $allergen->name }}
                                    </span>
                                @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
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
        <h3 class="font-lobster text-3xl text-gray-700 mb-4">No hay menú disponible</h3>
        <p class="text-gray-600 mb-8">Estamos preparando nuestro menú. Vuelve pronto.</p>
    </div>
@endif

@push('scripts')
<script>
jQuery(document).ready(function($) {
    // Manejo de filtros de categorías
    $('.category-filters li').on('click', function() {
        var filterValue = $(this).attr('data-filter');
        
        // Actualizar clase activa
        $('.category-filters li').removeClass('active');
        $(this).addClass('active');
        
        // Filtrar elementos
        if (filterValue === '*') {
            // Mostrar todos
            $('.menu-item').fadeIn(500);
        } else {
            // Ocultar todos primero
            $('.menu-item').fadeOut(300, function() {
                // Mostrar solo los filtrados
                $(filterValue).fadeIn(500);
            });
        }
    });
});
</script>
@endpush
