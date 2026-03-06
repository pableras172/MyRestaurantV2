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
        
        // Usar el helper centralizado que ya maneja subdominios vs slug
        return menu_url($tenant);
    }

    public function getRestaurantName(): ?string
    {
        $tenant = \Filament\Facades\Filament::getTenant();
        return $tenant?->name;
    }
}
