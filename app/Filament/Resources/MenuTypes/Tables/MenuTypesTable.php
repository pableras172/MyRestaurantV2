<?php

namespace App\Filament\Resources\MenuTypes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MenuTypesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label(__('menu_types.photo'))
                    ->defaultImageUrl(url('/images/default-menu.png')),
                
                TextColumn::make('name')
                    ->label(__('menu_types.name'))
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        $locale = app()->getLocale();
                        $name = $record->name;
                        return is_array($name) ? ($name[$locale] ?? $name['es'] ?? '') : $name;
                    }),
                
                TextColumn::make('description')
                    ->label(__('menu_types.description'))
                    ->limit(50)
                    ->getStateUsing(function ($record) {
                        $locale = app()->getLocale();
                        $description = $record->description;
                        return is_array($description) ? ($description[$locale] ?? $description['es'] ?? '') : $description;
                    })
                    ->toggleable(),
                
                TextColumn::make('price')
                    ->label(__('menu_types.price'))
                    ->money('EUR')
                    ->sortable()
                    ->toggleable(),
                
                ToggleColumn::make('is_principal')
                    ->label(__('menu_types.is_principal'))
                    ->sortable()
                    ->toggleable()
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state) {
                            // Desmarcar todos los demás del mismo restaurante
                            \App\Models\MenuType::where('restaurant_id', $record->restaurant_id)
                                ->where('id', '!=', $record->id)
                                ->update(['is_principal' => false]);
                            
                            $locale = app()->getLocale();
                            $name = is_array($record->name) ? ($record->name[$locale] ?? $record->name['es'] ?? '') : $record->name;
                            
                            Notification::make()
                                ->success()
                                ->title(__('menu_types.is_principal'))
                                ->body("'{$name}' establecido como menú principal.")
                                ->send();
                        }
                    }),
                
                TextColumn::make('sort_order')
                    ->label(__('menu_types.sort_order'))
                    ->sortable()
                    ->toggleable(),
                
                ToggleColumn::make('is_active')
                    ->label(__('menu_types.is_active'))
                    ->sortable()
                    ->afterStateUpdated(function ($record, $state) {
                        $locale = app()->getLocale();
                        $name = is_array($record->name) ? ($record->name[$locale] ?? $record->name['es'] ?? '') : $record->name;
                        
                        if ($state) {
                            Notification::make()
                                ->success()
                                ->title(__('menu_types.activated_title'))
                                ->body(__('menu_types.activated_body', ['name' => $name]))
                                ->send();
                        } else {
                            Notification::make()
                                ->warning()
                                ->title(__('menu_types.deactivated_title'))
                                ->body(__('menu_types.deactivated_body', ['name' => $name]))
                                ->send();
                        }
                    }),
                
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('menu_types.is_active'))
                    ->placeholder('Todos')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos'),
                    
                TernaryFilter::make('is_principal')
                    ->label(__('menu_types.is_principal'))
                    ->placeholder('Todos')
                    ->trueLabel('Solo principales')
                    ->falseLabel('No principales'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
