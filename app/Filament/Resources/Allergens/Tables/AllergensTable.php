<?php

namespace App\Filament\Resources\Allergens\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class AllergensTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('Icono')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-allergen.png')),
                
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->getStateUsing(function ($record) {
                        // Mostrar en el idioma actual
                        $locale = app()->getLocale();
                        $name = $record->name;
                        return is_array($name) ? ($name[$locale] ?? $name['es'] ?? '') : $name;
                    }),
                
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50)
                    ->getStateUsing(function ($record) {
                        $locale = app()->getLocale();
                        $description = $record->description;
                        return is_array($description) ? ($description[$locale] ?? $description['es'] ?? '') : $description;
                    })
                    ->toggleable(),
                
                ToggleColumn::make('is_active')
                    ->label(__('allergens.is_active'))
                    ->sortable()
                    ->afterStateUpdated(function ($record, $state) {
                        $locale = app()->getLocale();
                        $name = is_array($record->name) ? ($record->name[$locale] ?? $record->name['es'] ?? '') : $record->name;
                        
                        if ($state) {
                            Notification::make()
                                ->success()
                                ->title(__('allergens.activated_title'))
                                ->body(__('allergens.activated_body', ['name' => $name]))
                                ->send();
                        } else {
                            Notification::make()
                                ->warning()
                                ->title(__('allergens.deactivated_title'))
                                ->body(__('allergens.deactivated_body', ['name' => $name]))
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
                    ->label('Activo')
                    ->placeholder('Todos')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
    }
}
