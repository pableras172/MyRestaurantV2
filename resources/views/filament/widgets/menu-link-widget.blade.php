<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-primary-500/10">
                    <x-filament::icon
                        icon="heroicon-o-globe-alt"
                        class="w-5 h-5 text-primary-500"
                    />
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-semibold text-gray-900 dark:text-white leading-tight">
                        Ver Menú Público
                    </span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 leading-tight mt-0.5">
                        {{ $this->getRestaurantName() ?? 'Tu restaurante' }}
                    </span>
                </div>
            </div>
            
            @if($this->getMenuUrl())
                <div class="flex gap-2 flex-shrink-0">
                    <x-filament::button
                        tag="a"
                        href="{{ $this->getMenuUrl() }}"
                        target="_blank"
                        icon="heroicon-o-arrow-top-right-on-square"
                        size="sm"
                    >
                        Abrir
                    </x-filament::button>
                    
                    <x-filament::button
                        color="gray"
                        icon="heroicon-o-clipboard-document"
                        size="sm"
                        x-on:click="
                            navigator.clipboard.writeText('{{ $this->getMenuUrl() }}');
                            $tooltip('URL copiada', { timeout: 2000 });
                        "
                    >
                        Copiar
                    </x-filament::button>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
