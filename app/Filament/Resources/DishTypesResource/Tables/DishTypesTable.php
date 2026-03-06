<?php

namespace App\Filament\Resources\DishTypesResource\Tables;

use App\Models\DishType;
use App\Models\MenuType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DishTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Nombre con indentación jerárquica
                TextColumn::make('name')
                    ->label(__('dish_types.name'))
                    ->formatStateUsing(function (DishType $record): string {
                        $name = $record->getTranslation('name', app()->getLocale());
                        $level = $record->ancestors()->count();
                        
                        if ($level > 0) {
                            $prefix = str_repeat('—', $level) . ' ';
                            return $prefix . $name;
                        }
                        
                        return $name;
                    })
                    ->searchable()
                    ->sortable()
                    ->weight('medium')
                    ->grow(),

                // Descripción
                TextColumn::make('description')
                    ->label(__('dish_types.description'))
                    ->formatStateUsing(fn (DishType $record): ?string => 
                        $record->getTranslation('description', app()->getLocale())
                    )
                    ->searchable()
                    ->limit(50)
                    ->toggleable(),

                // Categoría padre
                TextColumn::make('parent.name')
                    ->label(__('dish_types.parent'))
                    ->formatStateUsing(function (DishType $record): ?string {
                        if ($record->parent) {
                            return $record->parent->getTranslation('name', app()->getLocale());
                        }
                        return '—';
                    })
                    ->sortable()
                    ->toggleable(),

                // Tipos de menú asociados
                TextColumn::make('menuTypes.name')
                    ->label(__('dish_types.menu_types'))
                    ->formatStateUsing(function (DishType $record): string {
                        $menuTypes = $record->menuTypes->map(function ($menuType) {
                            return $menuType->getTranslation('name', app()->getLocale());
                        })->join(', ');
                        
                        return $menuTypes ?: '—';
                    })
                    ->badge()
                    ->separator(',')
                    ->toggleable(),

                // Número de productos
                TextColumn::make('products_count')
                    ->label(__('dish_types.products_count'))
                    ->counts('products')
                    ->sortable()
                    ->toggleable(),

                // Orden
                TextColumn::make('sort_order')
                    ->label(__('dish_types.sort_order'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Activo (toggle)
                ToggleColumn::make('is_active')
                    ->label(__('dish_types.is_active'))
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state) {
                            Notification::make()
                                ->title(__('dish_types.activated_title'))
                                ->body(__('dish_types.activated_body', ['name' => $record->getTranslation('name', app()->getLocale())]))
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title(__('dish_types.deactivated_title'))
                                ->body(__('dish_types.deactivated_body', ['name' => $record->getTranslation('name', app()->getLocale())]))
                                ->warning()
                                ->send();
                        }
                    })
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('parent_id')
                    ->label(__('dish_types.parent'))
                    ->options(function () {
                        $tenant = \Filament\Facades\Filament::getTenant();
                        if (!$tenant) {
                            return [];
                        }
                        
                        return DishType::query()
                            ->where('restaurant_id', $tenant->id)
                            ->whereNull('parent_id') // Solo categorías raíz
                            ->orderBy('sort_order')
                            ->get()
                            ->mapWithKeys(function (DishType $dishType) {
                                return [$dishType->id => $dishType->getTranslation('name', app()->getLocale())];
                            });
                    })
                    ->placeholder(__('dish_types.all_categories')),
                
                SelectFilter::make('menu_types')
                    ->label(__('dish_types.filter_menu_type'))
                    ->relationship('menuTypes', 'name')
                    ->options(function () {
                        $tenant = \Filament\Facades\Filament::getTenant();
                        if (!$tenant) {
                            return [];
                        }
                        
                        return MenuType::query()
                            ->where('restaurant_id', $tenant->id)
                            ->active()
                            ->orderBy('sort_order')
                            ->get()
                            ->mapWithKeys(function (MenuType $menuType) {
                                return [$menuType->id => $menuType->getTranslation('name', app()->getLocale())];
                            });
                    })
                    ->placeholder(__('dish_types.all_menu_types')),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
    }
}
