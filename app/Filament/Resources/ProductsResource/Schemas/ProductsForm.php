<?php

namespace App\Filament\Resources\ProductsResource\Schemas;

use App\Models\Allergen;
use App\Models\DishType;
use App\Traits\HasTenantStorage;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class ProductsForm
{
    use HasTenantStorage;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        // Tab 1: Información básica
                        Tabs\Tab::make(__('products.tab_basic'))
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        // Nombre
                                        KeyValue::make('name')
                                            ->label(__('products.name'))
                                            ->keyLabel(__('products.language'))
                                            ->valueLabel(__('products.translation'))
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

                                        // Descripción
                                        KeyValue::make('description')
                                            ->label(__('products.description'))
                                            ->keyLabel(__('products.language'))
                                            ->valueLabel(__('products.translation'))
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

                                        // Ingredientes
                                        KeyValue::make('ingredients')
                                            ->label(__('products.ingredients'))
                                            ->keyLabel(__('products.language'))
                                            ->valueLabel(__('products.translation'))
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
                                            ->helperText(__('products.ingredients_helper'))
                                            ->columnSpanFull(),

                                        // Precio base
                                        TextInput::make('price')
                                            ->label(__('products.price'))
                                            ->numeric()
                                            ->prefix('€')
                                            ->minValue(0)
                                            ->step(0.01)
                                            ->required()
                                            ->helperText(__('products.price_helper'))
                                            ->columnSpan(1),

                                        // Tiempo de preparación
                                        TextInput::make('preparation_time')
                                            ->label(__('products.preparation_time'))
                                            ->numeric()
                                            ->suffix(__('products.minutes'))
                                            ->minValue(0)
                                            ->helperText(__('products.preparation_time_helper'))
                                            ->columnSpan(1),

                                        // Tasa de impuesto
                                        TextInput::make('tax_rate')
                                            ->label(__('products.tax_rate'))
                                            ->numeric()
                                            ->suffix('%')
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->step(0.01)
                                            ->helperText(__('products.tax_rate_helper'))
                                            ->columnSpan(1),

                                        // Orden
                                        TextInput::make('sort_order')
                                            ->label(__('products.sort_order'))
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->helperText(__('products.sort_order_helper'))
                                            ->columnSpan(1),
                                    ])
                                    ->columns(2),
                            ]),

                        // Tab 2: Imágenes
                        Tabs\Tab::make(__('products.tab_images'))
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        FileUpload::make('photo1')
                                            ->label(__('products.photo1'))
                                            ->image()
                                            ->disk('public')
                                            ->directory(fn() => self::getTenantStoragePath('products'))
                                            ->imageEditor()
                                            ->imageEditorAspectRatioOptions([
                                                '16:9',
                                                '4:3',
                                                '1:1',
                                            ])
                                            ->maxSize(1024)
                                            ->helperText(__('products.photo_helper'))
                                            ->columnSpanFull(),

                                        FileUpload::make('photo2')
                                            ->label(__('products.photo2'))
                                            ->image()
                                            ->disk('public')
                                            ->directory(fn() => self::getTenantStoragePath('products'))
                                            ->imageEditor()
                                            ->imageEditorAspectRatioOptions([
                                                '16:9',
                                                '4:3',
                                                '1:1',
                                            ])
                                            ->maxSize(1024)
                                            ->helperText(__('products.photo_helper'))
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // Tab 3: Categorías y Alérgenos
                        Tabs\Tab::make(__('products.tab_categories'))
                            ->icon('heroicon-o-tag')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        // Categorías
                                        Select::make('dish_types')
                                            ->label(__('products.dish_types'))
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->relationship('dishTypes', 'name')
                                            ->getOptionLabelFromRecordUsing(function (DishType $record) {
                                                return $record->getTranslation('name', app()->getLocale());
                                            })
                                            ->helperText(__('products.dish_types_helper'))
                                            ->columnSpanFull(),

                                        // Alérgenos
                                        Select::make('allergens')
                                            ->label(__('products.allergens'))
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->relationship('allergens', 'name')
                                            ->getOptionLabelFromRecordUsing(function (Allergen $record) {
                                                return $record->getTranslation('name', app()->getLocale());
                                            })
                                            ->helperText(__('products.allergens_helper'))
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // Tab 4: Variantes
                        Tabs\Tab::make(__('products.tab_variants'))
                            ->icon('heroicon-o-squares-2x2')
                            ->schema([
                                Section::make()
                                    ->description(__('products.variants_description'))
                                    ->schema([
                                        Repeater::make('variants')
                                            ->relationship()
                                            ->schema([
                                                KeyValue::make('name')
                                                    ->label(__('products.variant_name'))
                                                    ->keyLabel(__('products.language'))
                                                    ->valueLabel(__('products.translation'))
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

                                                TextInput::make('price')
                                                    ->label(__('products.variant_price'))
                                                    ->numeric()
                                                    ->prefix('€')
                                                    ->minValue(0)
                                                    ->step(0.01)
                                                    ->required()
                                                    ->columnSpan(1),

                                                Checkbox::make('is_default')
                                                    ->label(__('products.variant_default'))
                                                    ->helperText(__('products.variant_default_helper'))
                                                    ->columnSpan(1),

                                                TextInput::make('sort_order')
                                                    ->label(__('products.sort_order'))
                                                    ->numeric()
                                                    ->default(0)
                                                    ->minValue(0)
                                                    ->columnSpan(1),
                                            ])
                                            ->columns(3)
                                            ->defaultItems(0)
                                            ->reorderable()
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['name']['es'] ?? null)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        // Tab 5: Configuración
                        Tabs\Tab::make(__('products.tab_config'))
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Toggle::make('is_active')
                                            ->label(__('products.is_active'))
                                            ->default(true)
                                            ->helperText(__('products.is_active_helper'))
                                            ->columnSpan(1),

                                        Toggle::make('is_available')
                                            ->label(__('products.is_available'))
                                            ->default(true)
                                            ->helperText(__('products.is_available_helper'))
                                            ->columnSpan(1),

                                        Toggle::make('is_new')
                                            ->label(__('products.is_new'))
                                            ->default(false)
                                            ->helperText(__('products.is_new_helper'))
                                            ->columnSpan(1),

                                        Toggle::make('is_unit')
                                            ->label(__('products.is_unit'))
                                            ->default(true)
                                            ->helperText(__('products.is_unit_helper'))
                                            ->columnSpan(1),

                                        TextInput::make('stock_quantity')
                                            ->label(__('products.stock_quantity'))
                                            ->numeric()
                                            ->minValue(0)
                                            ->helperText(__('products.stock_quantity_helper'))
                                            ->columnSpan(2),
                                    ])
                                    ->columns(2),
                            ]),
                    ]),
            ]);
    }
}
