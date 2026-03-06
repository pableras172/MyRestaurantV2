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
        return $tenant ? route('public.menu.index', ['restaurant' => $tenant->slug]) : null;
    }

    public function getRestaurantName(): ?string
    {
        $tenant = \Filament\Facades\Filament::getTenant();
        return $tenant?->name;
    }
}
