<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class MenuLinkWidget extends Widget
{
    protected static ?int $sort = 0;
    
    protected string $view = 'filament.widgets.menu-link-widget';

    protected int | string | array $columnSpan = 'full';

    public function getMenuUrl(): ?string
    {
        $tenant = \Filament\Facades\Filament::getTenant();
        
        if (!$tenant) {
            return null;
        }
        
        // En producción usar subdominio, en local usar slug en URL
        if (config('app.env') === 'production' && config('app.domain')) {
            return 'https://' . $tenant->slug . '.' . config('app.domain');
        }
        
        // En desarrollo local usar slug en la URL
        return route('public.menu.index', ['restaurant' => $tenant->slug]);
    }

    public function getRestaurantName(): ?string
    {
        $tenant = \Filament\Facades\Filament::getTenant();
        return $tenant?->name;
    }
}
