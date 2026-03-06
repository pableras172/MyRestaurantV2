<?php

namespace App\Filament\Widgets;

use App\Models\Allergen;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class TopAllergensWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $tenant = \Filament\Facades\Filament::getTenant();

        return $table
            ->heading(__('Alérgenos Más Comunes'))
            ->description(__('Alérgenos presentes en más productos'))
            ->query(
                Allergen::query()
                    ->where('restaurant_id', $tenant?->id)
                    ->withCount('products')
                    ->orderBy('products_count', 'desc')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('name')
                    ->label(__('Alérgeno'))
                    ->formatStateUsing(fn ($record) => $record->getTranslation('name', app()->getLocale()))
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label(__('Descripción'))
                    ->formatStateUsing(fn ($record) => $record->getTranslation('description', app()->getLocale()))
                    ->wrap()
                    ->limit(50),

                TextColumn::make('products_count')
                    ->label(__('Productos'))
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 10 => 'danger',
                        $state >= 5 => 'warning',
                        default => 'success',
                    })
                    ->sortable(),
            ])
            ->defaultSort('products_count', 'desc')
            ->paginated(false);
    }
}
