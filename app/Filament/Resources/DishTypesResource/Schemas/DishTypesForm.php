<?php

namespace App\Filament\Resources\DishTypesResource\Schemas;

use App\Models\DishType;
use App\Models\MenuType;
use App\Traits\HasTenantStorage;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DishTypesForm
{
    use HasTenantStorage;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->schema([
                        // Nombre (traducido)
                        KeyValue::make('name')
                            ->label(__('dish_types.name'))
                            ->keyLabel(__('dish_types.language'))
                            ->valueLabel(__('dish_types.translation'))
                            ->default([
                                'es' => '',
                                'ca' => '',
                                'en' => '',
                            ])
                            ->afterStateHydrated(function ($component, $state) {
                                $state = is_array($state) ? $state : [];
                                $component->state(array_merge([
                                    'es' => '',
                                    'ca' => '',
                                    'en' => '',
                                ], $state));
                            })
                            ->addable(false)
                            ->deletable(false)
                            ->editableKeys(false)
                            ->required()
                            ->columnSpanFull(),

                        // Descripción (traducida)
                        KeyValue::make('description')
                            ->label(__('dish_types.description'))
                            ->keyLabel(__('dish_types.language'))
                            ->valueLabel(__('dish_types.translation'))
                            ->default([
                                'es' => '',
                                'ca' => '',
                                'en' => '',
                            ])
                            ->afterStateHydrated(function ($component, $state) {
                                $state = is_array($state) ? $state : [];
                                $component->state(array_merge([
                                    'es' => '',
                                    'ca' => '',
                                    'en' => '',
                                ], $state));
                            })
                            ->addable(false)
                            ->deletable(false)
                            ->editableKeys(false)
                            ->columnSpanFull(),

                        // Categoría padre
                        Select::make('parent_id')
                            ->label(__('dish_types.parent'))
                            ->searchable()
                            ->preload()
                            ->options(function ($livewire) {
                                $record = $livewire->record ?? null;
                                
                                // Obtener el tenant (restaurante) actual
                                $tenant = \Filament\Facades\Filament::getTenant();
                                $restaurantId = $tenant ? $tenant->id : null;
                                
                                if (!$restaurantId) {
                                    return [];
                                }
                                
                                $query = DishType::query()
                                    ->where('restaurant_id', $restaurantId)
                                    ->orderBy('sort_order');

                                // Si estamos editando, excluir el registro actual
                                if ($record) {
                                    $query->where('id', '!=', $record->id);
                                }

                                $results = $query->get();
                                
                                return $results->mapWithKeys(function (DishType $dishType) {
                                    $name = $dishType->getTranslation('name', app()->getLocale());
                                    $prefix = '';
                                    
                                    // Añadir indentación según nivel de jerarquía
                                    $level = $dishType->ancestors()->count();
                                    if ($level > 0) {
                                        $prefix = str_repeat('—', $level) . ' ';
                                    }
                                    
                                    return [$dishType->id => $prefix . $name];
                                });
                            })
                            ->nullable()
                            ->helperText(__('dish_types.parent_help'))
                            ->columnSpanFull(),

                        // Tipos de menú asociados
                        Select::make('menu_types')
                            ->label(__('dish_types.menu_types'))
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->relationship('menuTypes', 'name')
                            ->getOptionLabelFromRecordUsing(function (MenuType $record) {
                                return $record->getTranslation('name', app()->getLocale());
                            })
                            ->helperText(__('dish_types.menu_types_help'))
                            ->columnSpanFull(),

                        // Imagen
                        FileUpload::make('photo')
                            ->label(__('dish_types.photo'))
                            ->image()
                            ->disk('public')
                            ->directory(fn() => self::getTenantStoragePath('dish_types'))
                            ->imageEditor()
                            ->imageEditorAspectRatioOptions([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->maxSize(1024)
                            ->helperText(__('dish_types.photo_helper'))
                            ->columnSpanFull(),

                        // Orden
                        TextInput::make('sort_order')
                            ->label(__('dish_types.sort_order'))
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->minValue(0)
                            ->helperText(__('dish_types.sort_order_help'))
                            ->columnSpan(1),

                        // Activo
                        Checkbox::make('is_active')
                            ->label(__('dish_types.is_active'))
                            ->default(true)
                            ->inline(false)
                            ->columnSpan(1),
                    ])
                    ->columns(2),
            ]);
    }
}
