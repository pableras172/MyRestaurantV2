<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $restaurant->name }} - @yield('title', 'Menú')</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="{{ $restaurant->meta_description ?? $restaurant->description }}">
    <meta name="keywords" content="{{ $restaurant->meta_keywords ?? '' }}">

    @if ($restaurant->meta_image)
        <meta property="og:image" content="{{ asset('storage/' . $restaurant->meta_image) }}">
    @endif

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('template/images/favicon.png') }}">

    <!-- Flaticon -->
    <link rel="stylesheet" href="{{ asset('template/icons/flaticon/flaticon_swigo.css') }}">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('template/icons/font-awesome/css/all.min.css') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Lobster&family=Poppins:wght@100;200;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Swiper -->
    <link href="{{ asset('template/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Nice Select -->
    <link href="{{ asset('template/vendor/niceselect/css/nice-select.css') }}" rel="stylesheet">

    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="{{ asset('template/css/style.css') }}">

    @stack('styles')
</head>

<body id="bg" class="box-border m-0 selection:bg-primary selection:text-white font-poppins"
    data-color="{{ $restaurant->theme_color ?? 'color_1' }}">
    <!-- Loader -->
    <div id="loading-area"
        class="loading-page-3 fixed top-0 left-0 w-full h-full z-[999999999] flex items-center justify-center bg-white"
        style="display: flex;">
        <img src="{{ asset('template/images/loading.gif') }}" alt="Loading...">
    </div>

    <!-- Custom Cursor -->
    <div id="cursor"
        class="hidden lg:block fixed w-5 h-5 bg-primary rounded-full pointer-events-none z-[99999] transition-opacity duration-300"
        style="left: -100px; top: -100px;"></div>
    <div id="cursor-border"
        class="hidden lg:block fixed w-12 h-12 border-2 border-primary/50 rounded-full pointer-events-none z-[99998]"
        style="left: -100px; top: -100px;"></div>

    <!-- scrolltop-progress -->
    <div class="progress-wrap">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>

    <div class="page-wraper">

        <!-- Header -->
        <header class="site-header main-bar-wraper top-0 left-0 w-full z-[999] is-fixed">
            <div class="main-bar">
                <div class="container">
                    <!-- Website Logo -->
                    <div class="logo-header w-[180px] h-[64px] items-center relative flex float-left">
                        @if ($restaurant->logo)
                            <a href="{{ route('public.menu.index', ['restaurant' => $restaurant->slug]) }}"
                                class="pt-[5px] relative block">
                                <img src="{{ asset('storage/' . $restaurant->logo) }}" alt="{{ $restaurant->name }}"
                                    class="w-[60px] h-[60px] object-cover rounded-full">
                            </a>
                        @else
                            <a href="{{ route('public.menu.index', ['restaurant' => $restaurant->slug]) }}"
                                class="pt-[5px] relative">
                                <h1 class="text-2xl font-bold">{{ $restaurant->name }}</h1>
                            </a>
                        @endif
                    </div>

                    <!-- Language Selector (Mobile - between logo and toggle) -->
                    <div class="lg:hidden flex items-center h-[64px] float-left ml-4">
                        <div class="relative">
                            <select id="language-selector-mobile"
                            onchange="changeLanguage(this.value)"
                                class="appearance-none bg-white border border-gray-300 rounded-md px-3 py-2 pr-8 text-sm font-medium cursor-pointer hover:border-primary focus:outline-none focus:border-primary transition-colors">
                                <option value="es" {{ app()->getLocale() == 'es' ? 'selected' : '' }}>ES</option>
                                <option value="ca" {{ app()->getLocale() == 'ca' ? 'selected' : '' }}>CA</option>
                                <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>EN</option>
                            </select>
                            <i
                                class="flaticon-down-arrow absolute right-2 top-1/2 transform -translate-y-1/2 text-xs pointer-events-none"></i>
                        </div>
                    </div>

                    <!-- Toggle button -->
                    <button
                        class="togglebtn lg:hidden block bg-primary w-[45px] h-[45px] relative rounded-md float-right mt-2">
                        <span class="bar1"></span>
                        <span class="bar2"></span>
                        <span class="bar3"></span>
                    </button>

                    <!-- EXTRA NAV -->
                    <div class="extra-nav float-right items-center h-[64px] lg:flex relative hidden pl-[70px]">
                        <div class="extra-cell flex items-center">
                            <ul class="flex items-center gap-[10px]">
                                <!-- Language Selector (Desktop) -->
                                <li class="inline-block">
                                    <div class="relative">

                                        <select id="language-selector-desktop"
                                            class="appearance-none bg-white border border-gray-300 rounded-md px-3 py-2 pr-8 h-[45px] text-sm font-medium cursor-pointer hover:border-primary focus:outline-none focus:border-primary transition-colors shadow-[0_10px_10px_0_rgba(0,0,0,0.1)]"
                                            onchange="changeLanguage(this.value)">
                                            <option value="es" {{ session('locale') == 'es' ? 'selected' : '' }}>
                                                🇪🇸 Español</option>
                                            <option value="ca" {{ session('locale')== 'ca' ? 'selected' : '' }}>
                                                🇪🇸 Català</option>
                                            <option value="en" {{ session('locale') == 'en' ? 'selected' : '' }}>
                                                🇬🇧 English</option>
                                        </select>
                                        <i
                                            class="flaticon-down-arrow absolute right-2 top-1/2 transform -translate-y-1/2 text-xs pointer-events-none"></i>
                                    </div>
                                </li>
                                @if ($restaurant->phone_1)
                                    <li class="inline-block">
                                        <a href="tel:{{ $restaurant->phone_1 }}"
                                            class="bg-white flex items-center justify-center px-4 h-[45px] rounded-md shadow-[0_10px_10px_0_rgba(0,0,0,0.1)]">
                                            <i class="flaticon-phone-call text-xl inline-flex mr-2"></i>
                                            <span class="text-sm font-medium">{{ $restaurant->phone_1 }}</span>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Main Nav -->

                    <!-- Main Nav -->
                    <div class="header-nav lg:justify-end lg:flex-row flex-col lg:float-right float-none">
                        <div class="logo-header logo-dark">
                            <a href="{{ route('public.menu.index', ['restaurant' => $restaurant->slug]) }}"
                                class="block">
                                <img src="{{ asset('storage/' . $restaurant->logo) }}" alt="{{ $restaurant->name }}"
                                    class="w-[60px] h-[60px] object-cover rounded-full">
                            </a>
                        </div>

                        <ul class="nav navbar-nav navbar lg:flex">
                            <li><a
                                    href="{{ route('public.menu.index', ['restaurant' => $restaurant->slug]) }}">Inicio</a>
                            </li>

                            @if (isset($menuTypes) && $menuTypes->count() > 0)
                                @foreach ($menuTypes as $menuType)
                                    <li>
                                        <a href="{{ route('public.menu.index', ['restaurant' => $restaurant->slug, 'menu_type' => $menuType->id]) }}"
                                            class="{{ isset($principalMenu) && $principalMenu->id == $menuType->id ? 'active' : '' }}">
                                            {{ $menuType->name }}
                                        </a>
                                    </li>
                                @endforeach
                            @else
                                <li><a
                                        href="{{ route('public.menu.index', ['restaurant' => $restaurant->slug]) }}">Menú</a>
                                </li>
                            @endif
                        </ul>

                        <div class="dz-social-icon">
                            <ul>
                                @if ($restaurant->facebook_url)
                                    <li>
                                        <a target="_blank" href="{{ $restaurant->facebook_url }}"
                                            class="flex items-center justify-center">
                                            <img src="{{ asset('images/social/facebook.svg') }}" alt="Facebook"
                                                class="w-5 h-5">
                                        </a>
                                    </li>
                                @endif
                                @if ($restaurant->instagram_url)
                                    <li>
                                        <a target="_blank" href="{{ $restaurant->instagram_url }}"
                                            class="flex items-center justify-center">
                                            <img src="{{ asset('images/social/instagram.svg') }}" alt="Instagram"
                                                class="w-5 h-5">
                                        </a>
                                    </li>
                                @endif
                                @if ($restaurant->google_profile_url)
                                    <li>
                                        <a target="_blank" href="{{ $restaurant->google_profile_url }}"
                                            class="flex items-center justify-center">
                                            <img src="{{ asset('images/social/google.svg') }}" alt="Google"
                                                class="w-5 h-5">
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <a href="#" onclick="shareRestaurant(event)" title="Compartir"
                                        class="flex items-center justify-center">
                                        <img src="{{ asset('images/social/whatsapp.svg') }}" alt="Compartir"
                                            class="w-5 h-5">
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Header End -->

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="site-footer style-1 bg-dark" id="footer">
            <div class="container">
                <div class="footer-top lg:py-[50px] py-[30px]">
                    <div class="row">
                        <!-- Logo y descripción -->
                        <div
                            class="xl:w-4/12 lg:w-4/12 md:w-6/12 w-full px-[15px] lg:mb-[30px] mb-[20px] md:text-left text-center">
                            <div class="widget widget_about">
                                @if ($restaurant->logo)
                                    <div class="footer-logo logo-white mb-[20px] md:mx-0 mx-auto">
                                        <a href="{{ route('public.menu.index', ['restaurant' => $restaurant->slug]) }}"
                                            class="block w-[120px] h-[120px] md:mx-0 mx-auto">
                                            <img src="{{ asset('storage/' . $restaurant->logo) }}"
                                                alt="{{ $restaurant->name }}"
                                                class="w-full h-full object-cover rounded-full">
                                        </a>
                                    </div>
                                @endif
                                <h4 class="text-white text-xl font-semibold mb-3">{{ $restaurant->name }}</h4>
                                @if ($restaurant->description)
                                    <p class="text-sm text-[#999999] leading-relaxed">{{ $restaurant->description }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Tipos de Menú -->
                        <div
                            class="xl:w-4/12 lg:w-4/12 md:w-6/12 w-full px-[15px] lg:mb-[30px] mb-[20px] md:text-left text-center">
                            <div class="widget widget_services">
                                <h5
                                    class="footer-title mb-[15px] text-xl font-semibold text-white uppercase leading-[1.1]">
                                    Nuestros Menús</h5>
                                <ul class="md:space-y-2 space-y-1">
                                    @if (isset($menuTypes) && $menuTypes->count() > 0)
                                        @foreach ($menuTypes as $menuType)
                                            <li>
                                                <a href="{{ route('public.menu.index', ['restaurant' => $restaurant->slug, 'menu_type' => $menuType->id]) }}"
                                                    class="text-[#999999] hover:text-primary transition-colors duration-300 flex items-center md:justify-start justify-center">
                                                    <i class="fas fa-chevron-right text-xs mr-2"></i>
                                                    <span>{{ $menuType->name }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    @else
                                        <li>
                                            <a href="{{ route('public.menu.index', ['restaurant' => $restaurant->slug]) }}"
                                                class="text-[#999999] hover:text-primary transition-colors duration-300 flex items-center md:justify-start justify-center">
                                                <i class="fas fa-chevron-right text-xs mr-2"></i>
                                                <span>Ver Menú</span>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <!-- Información de contacto -->
                        <div
                            class="xl:w-4/12 lg:w-4/12 md:w-6/12 w-full px-[15px] lg:mb-[30px] mb-[20px] md:text-left text-center">
                            <div class="widget widget_getintuch">
                                <h5
                                    class="footer-title mb-[15px] text-xl font-semibold text-white uppercase leading-[1.1]">
                                    Contacto</h5>
                                <ul class="md:space-y-3 space-y-2">
                                    @if ($restaurant->address_line_1)
                                        <li class="flex items-start text-[#999999] md:justify-start justify-center">
                                            <i class="flaticon-placeholder text-primary text-xl mr-3 mt-1"></i>
                                            <span
                                                class="leading-relaxed md:text-left text-center">{{ $restaurant->address_line_1 }}
                                                @if ($restaurant->address_line_2)
                                                    , {{ $restaurant->address_line_2 }}
                                                @endif
                                            </span>
                                        </li>
                                    @endif
                                    @if ($restaurant->phone_1)
                                        <li class="flex items-center text-[#999999] md:justify-start justify-center">
                                            <i class="flaticon-phone-call text-primary text-xl mr-3"></i>
                                            <a href="tel:{{ $restaurant->phone_1 }}"
                                                class="hover:text-primary transition-colors duration-300">
                                                {{ $restaurant->phone_1 }}
                                            </a>
                                        </li>
                                    @endif
                                    @if ($restaurant->phone_2)
                                        <li class="flex items-center text-[#999999] md:justify-start justify-center">
                                            <i class="flaticon-phone-call text-primary text-xl mr-3"></i>
                                            <a href="tel:{{ $restaurant->phone_2 }}"
                                                class="hover:text-primary transition-colors duration-300">
                                                {{ $restaurant->phone_2 }}
                                            </a>
                                        </li>
                                    @endif
                                    @if ($restaurant->email)
                                        <li class="flex items-center text-[#999999] md:justify-start justify-center">
                                            <i class="flaticon-email text-primary text-xl mr-3"></i>
                                            <a href="mailto:{{ $restaurant->email }}"
                                                class="hover:text-primary transition-colors duration-300">
                                                {{ $restaurant->email }}
                                            </a>
                                        </li>
                                    @endif
                                </ul>

                                <div class="dz-social-icon mt-4">
                                    <ul class="flex gap-2 md:justify-start justify-center">
                                        @if ($restaurant->facebook_url)
                                            <li class="inline-block">
                                                <a href="{{ $restaurant->facebook_url }}" target="_blank"
                                                    class="w-10 h-10 flex items-center justify-center bg-white/10 hover:bg-primary rounded transition-colors duration-300">
                                                    <img src="{{ asset('images/social/facebook.svg') }}"
                                                        alt="Facebook" class="w-5 h-5">
                                                </a>
                                            </li>
                                        @endif
                                        @if ($restaurant->instagram_url)
                                            <li class="inline-block">
                                                <a href="{{ $restaurant->instagram_url }}" target="_blank"
                                                    class="w-10 h-10 flex items-center justify-center bg-white/10 hover:bg-primary rounded transition-colors duration-300">
                                                    <img src="{{ asset('images/social/instagram.svg') }}"
                                                        alt="Instagram" class="w-5 h-5">
                                                </a>
                                            </li>
                                        @endif
                                        @if ($restaurant->google_profile_url)
                                            <li class="inline-block">
                                                <a href="{{ $restaurant->google_profile_url }}" target="_blank"
                                                    class="w-10 h-10 flex items-center justify-center bg-white/10 hover:bg-primary rounded transition-colors duration-300">
                                                    <img src="{{ asset('images/social/google.svg') }}" alt="Google"
                                                        class="w-5 h-5">
                                                </a>
                                            </li>
                                        @endif
                                        <li class="inline-block">
                                            <a href="#" onclick="shareRestaurant(event)" title="Compartir"
                                                class="w-10 h-10 flex items-center justify-center bg-white/10 hover:bg-primary rounded transition-colors duration-300">
                                                <img src="{{ asset('images/social/whatsapp.svg') }}" alt="Compartir"
                                                    class="w-5 h-5">
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="container">
                <div class="footer-bottom relative py-5 border-t border-[#ffffff1a]">
                    <div class="row">
                        <div class="md:w-1/2 w-full md:text-left text-center px-[15px]">
                            <p class="text-sm text-[#999999]">Copyright © {{ date('Y') }}
                                {{ $restaurant->name }}. Todos los derechos reservados.</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- Footer End -->

    </div>
    <div class="menu-backdrop"></div>

    <!-- Scripts -->
    <script src="{{ asset('template/js/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/niceselect/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('template/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('template/js/dz.carousel.js') }}"></script>
    <script src="{{ asset('template/js/custom.js') }}"></script>
    <script src="{{ asset('template/js/dznav-init.js') }}"></script>

    <script>
       

        function shareRestaurant(event) {
            event.preventDefault();

            const shareData = {
                title: '{{ $restaurant->name }}',
                text: '{{ $restaurant->description ?? 'Visita nuestro restaurante' }}',
                url: window.location.href
            };

            // Check if Web Share API is supported
            if (navigator.share) {
                navigator.share(shareData)
                    .then(() => console.log('Compartido exitosamente'))
                    .catch((error) => console.log('Error al compartir:', error));
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href)
                    .then(() => {
                        alert('Enlace copiado al portapapeles');
                    })
                    .catch((error) => {
                        console.log('Error al copiar:', error);
                        // Ultimate fallback: show the URL
                        prompt('Copia este enlace:', window.location.href);
                    });
            }
        }

        // Language selector functionality
        function changeLanguage(lang) {
            const currentUrl = encodeURIComponent(window.location.href);
            window.location.href = '/change-language?locale=' + lang + '&redirect=' + currentUrl;
        }

        // Mantener el header siempre fijo
        jQuery(document).ready(function() {
            // Asegurar que el header siempre tenga is-fixed
            jQuery('header').addClass('is-fixed');

            // Sobrescribir el comportamiento del scroll
            jQuery(window).off('scroll.stickyheader');
            jQuery(window).on('scroll.stickyheader', function() {
                jQuery('header').addClass('is-fixed');
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
