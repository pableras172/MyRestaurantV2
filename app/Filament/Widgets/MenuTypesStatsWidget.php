<?php

namespace App\Filament\Widgets;

use App\Models\MenuType;
use Filament\Widgets\ChartWidget;

class MenuTypesStatsWidget extends ChartWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return 'Categorías por Tipo de Menú';
    }

    protected function getData(): array
    {
        $tenant = \Filament\Facades\Filament::getTenant();
        
        if (!$tenant) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        $menuTypes = MenuType::where('restaurant_id', $tenant->id)
            ->withCount('dishTypes')
            ->where('is_active', true)
            ->orderBy('dish_types_count', 'desc')
            ->get();

        $labels = $menuTypes->map(function ($menuType) {
            return $menuType->getTranslation('name', app()->getLocale());
        })->toArray();

        $data = $menuTypes->pluck('dish_types_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('Categorías'),
                    'data' => $data,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2,
                    'fill' => true,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public function getDescription(): ?string
    {
        return 'Número de categorías asociadas a cada tipo de menú';
    }
}
