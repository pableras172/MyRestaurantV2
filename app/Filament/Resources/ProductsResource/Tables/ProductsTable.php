<?php

namespace App\Filament\Resources\ProductsResource\Tables;

use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Imagen
                ImageColumn::make('photo1')
                    ->label(__('products.photo'))
                    ->defaultImageUrl(url('/images/default-product.png'))
                    ->circular()
                    ->size(50),

                // Nombre
                TextColumn::make('name')
                    ->label(__('products.name'))
                    ->formatStateUsing(fn (Product $record): string => 
                        $record->getTranslation('name', app()->getLocale())
                    )
                    ->searchable()
                    ->sortable()
                    ->weight('medium'),

                // Categorías
                TextColumn::make('dishTypes.name')
                    ->label(__('products.dish_types'))
                    ->formatStateUsing(function (Product $record): string {
                        $types = $record->dishTypes->map(function ($type) {
                            return $type->getTranslation('name', app()->getLocale());
                        })->join(', ');
                        
                        return $types ?: '—';
                    })
                    ->badge()
                    ->separator(',')
                    ->toggleable(),

                // Precio
                TextColumn::make('price')
                    ->label(__('products.price'))
                    ->money('EUR')
                    ->sortable(),

                // Variantes
                TextColumn::make('variants_count')
                    ->label(__('products.variants'))
                    ->counts('variants')
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                // Alérgenos
                TextColumn::make('allergens_count')
                    ->label(__('products.allergens_count'))
                    ->counts('allergens')
                    ->badge()
                    ->color('warning')
                    ->toggleable(),

                // Tiempo preparación
                TextColumn::make('preparation_time')
                    ->label(__('products.preparation_time'))
                    ->formatStateUsing(fn ($state): ?string => $state ? $state . ' min' : null)
                    ->toggleable(isToggledHiddenByDefault: true),

                // Stock
                TextColumn::make('stock_quantity')
                    ->label(__('products.stock'))
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state === null => 'gray',
                        $state > 10 => 'success',
                        $state > 0 => 'warning',
                        default => 'danger',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),

                // Orden
                TextColumn::make('sort_order')
                    ->label(__('products.sort_order'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Novedad
                ToggleColumn::make('is_new')
                    ->label(__('products.is_new'))
                    ->afterStateUpdated(function ($record, $state) {
                        $name = $record->getTranslation('name', app()->getLocale());
                        if ($state) {
                            Notification::make()
                                ->title(__('products.marked_as_new_title'))
                                ->body(__('products.marked_as_new_body', ['name' => $name]))
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title(__('products.unmarked_as_new_title'))
                                ->body(__('products.unmarked_as_new_body', ['name' => $name]))
                                ->info()
                                ->send();
                        }
                    })
                    ->sortable()
                    ->toggleable(),

                // Disponible
                ToggleColumn::make('is_available')
                    ->label(__('products.is_available'))
                    ->afterStateUpdated(function ($record, $state) {
                        $name = $record->getTranslation('name', app()->getLocale());
                        if ($state) {
                            Notification::make()
                                ->title(__('products.available_title'))
                                ->body(__('products.available_body', ['name' => $name]))
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title(__('products.unavailable_title'))
                                ->body(__('products.unavailable_body', ['name' => $name]))
                                ->warning()
                                ->send();
                        }
                    })
                    ->sortable()
                    ->toggleable(),

                // Activo
                ToggleColumn::make('is_active')
                    ->label(__('products.is_active'))
                    ->afterStateUpdated(function ($record, $state) {
                        $name = $record->getTranslation('name', app()->getLocale());
                        if ($state) {
                            Notification::make()
                                ->title(__('products.activated_title'))
                                ->body(__('products.activated_body', ['name' => $name]))
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title(__('products.deactivated_title'))
                                ->body(__('products.deactivated_body', ['name' => $name]))
                                ->warning()
                                ->send();
                        }
                    })
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('products.is_active')),
                
                TernaryFilter::make('is_available')
                    ->label(__('products.is_available')),
                
                TernaryFilter::make('is_new')
                    ->label(__('products.is_new')),
                
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}
