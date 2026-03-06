@extends('public.layout')

@section('title', 'Menú')

@section('content')
    <!-- Banner -->
    <section class="bg-fixed relative z-[1] after:content-[''] after:absolute after:z-[-1] after:bg-[#222222e6] after:opacity-100 after:w-full after:h-full after:top-0 after:left-0 pt-[50px] lg:h-[450px] sm:h-[400px] h-[300px] overflow-hidden bg-cover bg-center"
         style="background-image: url('{{ $currentRestaurant->header_image ? asset('storage/' . $currentRestaurant->header_image) : asset('template/images/banner/bnr1.jpg') }}');">
        <div class="container table h-full relative z-[1] text-center">
            <div class="dz-bnr-inr-entry align-middle table-cell">
                <h2 class="text-white mt-4 mb-2 2xl:text-[70px] md:text-[60px] text-[40px] leading-[1.2]">
                    @if ($principalMenu)
                        {{ $principalMenu->name }}
                    @else
                        Nuestro Menú
                    @endif
                </h2>
                @if ($principalMenu && $principalMenu->description)
                    <p class="text-white/90 text-lg max-w-2xl mx-auto mb-2">{{ $principalMenu->description }}</p>
                @endif
                @if ($principalMenu && $principalMenu->price)
                    <div class="text-white text-3xl font-bold mb-2">{{ number_format($principalMenu->price, 2) }}€</div>
                @endif
                <!-- Breadcrumb Row -->
                <nav aria-label="breadcrumb" class="breadcrumb-row">
                    <ul
                        class="breadcrumb bg-primary shadow-[0px_10px_20px_rgba(0,0,0,0.05)] rounded-[10px] inline-block lg:py-[13px] md:py-[10px] sm:py-[5px] py-[7px] lg:px-[30px] md:px-[18px] sm:px-5 px-3.5 m-0">
                        <a href="{{ menu_url($currentRestaurant) }}" class="text-white">Inicio</a>
                        <li class="breadcrumb-item text-white p-0 inline-block text-[15px] font-normal active pl-2">Menú
                            1</li>
                    </ul>
                </nav>
                <!-- Breadcrumb Row End -->
            </div>
        </div>
    </section>


    <!-- section class="bg-fixed relative z-[1] after:content-[''] after:absolute after:z-[-1] after:bg-[#222222e6] after:opacity-100 after:w-full after:h-full after:top-0 after:left-0 lg:mt-[50px] md:mt-[50px] mt-[50px] pt-[75px] lg:h-[450px] sm:h-[400px] h-[300px] overflow-hidden bg-cover bg-center"
             style="background-image: url('{{ $currentRestaurant->header_image ? asset('storage/' . $currentRestaurant->header_image) : asset('template/images/banner/bnr1.jpg') }}');">
        <div class="container table h-full relative z-[1] text-center">
            <div class="dz-bnr-inr-entry align-middle table-cell">
                <h2 class="text-white mb-5 2xl:text-[70px] md:text-[60px] text-[40px] leading-[1.2]">
                    @if ($principalMenu)
    {{ $principalMenu->name }}
@else
    Nuestro Menú
    @endif
                </h2>
                @if ($principalMenu && $principalMenu->description)
    <p class="text-white/90 text-lg max-w-2xl mx-auto mb-5">{{ $principalMenu->description }}</p>
    @endif
                @if ($principalMenu && $principalMenu->price)
    <div class="text-white text-3xl font-bold mb-5">{{ number_format($principalMenu->price, 2) }}€</div>
    @endif
                <nav aria-label="breadcrumb" class="breadcrumb-row">
                    <ul class="breadcrumb bg-primary shadow-[0px_10px_20px_rgba(0,0,0,0.05)] rounded-[10px] inline-block lg:py-[13px] md:py-[10px] sm:py-[5px] py-[7px] lg:px-[30px] md:px-[18px] sm:px-5 px-3.5 m-0">
                        <li class="breadcrumb-item p-0 inline-block text-[15px] font-normal">
                            <a href="{{ route('public.menu.index', ['restaurant' => $currentRestaurant->slug]) }}" class="text-white">Inicio</a>
                        </li>
                        <li class="breadcrumb-item text-white p-0 inline-block text-[15px] font-normal active pl-2">Menú</li>
                    </ul>
                </nav>
            </div>
        </div>
    </section -->
    <!-- Banner End -->

    <!-- Our Menu -->
    <section
        class=" sm:pb-10 pb-5 section-wrapper-7 overflow-hidden relative bg-white">
        <div class="container">
            @php
                // Determinar el estilo de menú a usar
                $menuStyle = $currentRestaurant->menu_style ?? 'menu-1';
            @endphp

            @include('public.menu.styles.' . $menuStyle, [
                'categoriesWithProducts' => $categoriesWithProducts,
                'currentRestaurant' => $currentRestaurant,
            ])
        </div>

        <!-- Background decorations -->
        <img src="{{ asset('template/images/background/pic5.png') }}" alt=""
            class="bg1 bottom-[10px] left-0 absolute max-2xl:hidden animate-dz">
        <img src="{{ asset('template/images/background/pic6.png') }}" alt=""
            class="top-[15px] right-[10px] absolute max-2xl:hidden animate-dz">
    </section>
    <!-- Our Menu End -->

    <!-- Info Banner -->
    @if ($principalMenu && ($principalMenu->superior_notes || $principalMenu->notes))
        <section class="py-[50px] bg-light">
            <div class="container">
                <div class="text-center">
                    @if ($principalMenu->superior_notes)
                        <div class="text-sm text-gray-600 mb-4 max-w-3xl mx-auto">
                            {{ $principalMenu->superior_notes }}
                        </div>
                    @endif
                    @if ($principalMenu->notes)
                        <div class="text-bodycolor max-w-2xl mx-auto">
                            {{ $principalMenu->notes }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- Contact Banner -->
    <section
        class="bg-fixed relative z-[1] after:content-[''] after:absolute after:z-[-1] after:bg-[#222222e6] after:opacity-100 after:w-full after:h-full after:top-0 after:left-0 py-[50px] overflow-hidden bg-cover bg-center"
        style="background-image: url('{{ $currentRestaurant->header_image ? asset('storage/' . $currentRestaurant->header_image) : asset('template/images/banner/bnr1.jpg') }}');">
        <div class="container relative z-[1]">
            <div class="text-center">
                <h3 class="text-white text-3xl mb-4">¿Tienes alguna alergia o intolerancia?</h3>
                <p class="text-white/90 max-w-2xl mx-auto mb-5">
                    Todos nuestros platos están identificados con los alérgenos que contienen.
                    Consulta con nuestro personal si tienes alguna duda.
                </p>
                @if ($currentRestaurant->phone_1)
                    <a href="tel:{{ $currentRestaurant->phone_1 }}" class="btn btn-white inline-block">
                        <span><i class="flaticon-phone mr-2"></i>{{ $currentRestaurant->phone_1 }}</span>
                    </a>
                @endif
            </div>
        </div>
    </section>
@endsection
