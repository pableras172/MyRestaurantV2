<?php

namespace App\Filament\Widgets;

use App\Models\DishType;
use Filament\Widgets\ChartWidget;

class ProductsByCategoryWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): ?string
    {
        return 'Productos por Categoría';
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

        // Obtener categorías principales (sin padre) con sus productos
        $categories = DishType::where('restaurant_id', $tenant->id)
            ->whereNull('parent_id')
            ->withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(8)
            ->get();

        $labels = $categories->map(function ($category) {
            return $category->getTranslation('name', app()->getLocale());
        })->toArray();

        $data = $categories->pluck('products_count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => __('Productos'),
                    'data' => $data,
                    'backgroundColor' => [
                        'rgba(59, 130, 246, 0.5)',
                        'rgba(16, 185, 129, 0.5)',
                        'rgba(249, 115, 22, 0.5)',
                        'rgba(139, 92, 246, 0.5)',
                        'rgba(236, 72, 153, 0.5)',
                        'rgba(234, 179, 8, 0.5)',
                        'rgba(239, 68, 68, 0.5)',
                        'rgba(168, 85, 247, 0.5)',
                    ],
                    'borderColor' => [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(249, 115, 22)',
                        'rgb(139, 92, 246)',
                        'rgb(236, 72, 153)',
                        'rgb(234, 179, 8)',
                        'rgb(239, 68, 68)',
                        'rgb(168, 85, 247)',
                    ],
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public function getDescription(): ?string
    {
        return 'Distribución de productos entre las categorías principales';
    }
}
