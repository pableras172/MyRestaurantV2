<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductsStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $tenant = \Filament\Facades\Filament::getTenant();
        
        if (!$tenant) {
            return [];
        }

        $totalProducts = Product::where('restaurant_id', $tenant->id)->count();
        $activeProducts = Product::where('restaurant_id', $tenant->id)
            ->where('is_active', true)
            ->count();
        $newProducts = Product::where('restaurant_id', $tenant->id)
            ->where('is_new', true)
            ->count();
        $availableProducts = Product::where('restaurant_id', $tenant->id)
            ->where('is_available', true)
            ->count();

        // Calcular precio medio
        $averagePrice = Product::where('restaurant_id', $tenant->id)
            ->where('is_active', true)
            ->avg('price');

        return [
            Stat::make(__('Total Productos'), $totalProducts)
                ->description(__('Productos en el menú'))
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('primary'),

            Stat::make(__('Productos Activos'), $activeProducts)
                ->description(round(($activeProducts / max($totalProducts, 1)) * 100, 1) . '% del total')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make(__('Precio Medio'), number_format($averagePrice, 2, ',', '.') . ' €')
                ->description(__('Precio promedio de productos activos'))
                ->descriptionIcon('heroicon-o-currency-euro')
                ->color('warning'),

            Stat::make(__('Productos Nuevos'), $newProducts)
                ->description(__('Marcados como novedades'))
                ->descriptionIcon('heroicon-o-sparkles')
                ->color('info'),
        ];
    }
}
