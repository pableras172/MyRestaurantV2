<?php

namespace App\Filament\Resources\DishTypesResource\RelationManagers;

use App\Models\Allergen;
use App\Models\DishType;
use App\Models\Product;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('products.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make(__('products.tab_basic'))
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        KeyValue::make('name')
                                            ->label(__('products.name'))
                                            ->keyLabel(__('products.language'))
                                            ->valueLabel(__('products.translation'))
                                            ->default(['es' => '', 'ca' => '', 'en' => ''])
                                            ->afterStateHydrated(function ($component, $state) {
                                                $state = is_array($state) ? $state : [];
                                                $component->state(array_merge(['es' => '', 'ca' => '', 'en' => ''], $state));
                                            })
                                            ->addable(false)
                                            ->deletable(false)
                                            ->editableKeys(false)
                                            ->required()
                                            ->columnSpanFull(),

                                        KeyValue::make('description')
                                            ->label(__('products.description'))
                                            ->keyLabel(__('products.language'))
                                            ->valueLabel(__('products.translation'))
                                            ->default(['es' => '', 'ca' => '', 'en' => ''])
                                            ->afterStateHydrated(function ($component, $state) {
                                                $state = is_array($state) ? $state : [];
                                                $component->state(array_merge(['es' => '', 'ca' => '', 'en' => ''], $state));
                                            })
                                            ->addable(false)
                                            ->deletable(false)
                                            ->editableKeys(false)
                                            ->columnSpanFull(),

                                        KeyValue::make('ingredients')
                                            ->label(__('products.ingredients'))
                                            ->keyLabel(__('products.language'))
                                            ->valueLabel(__('products.translation'))
                                            ->default(['es' => '', 'ca' => '', 'en' => ''])
                                            ->afterStateHydrated(function ($component, $state) {
                                                $state = is_array($state) ? $state : [];
                                                $component->state(array_merge(['es' => '', 'ca' => '', 'en' => ''], $state));
                                            })
                                            ->addable(false)
                                            ->deletable(false)
                                            ->editableKeys(false)
                                            ->columnSpanFull(),

                                        TextInput::make('price')
                                            ->label(__('products.price'))
                                            ->numeric()
                                            ->prefix('€')
                                            ->minValue(0)
                                            ->step(0.01)
                                            ->required(),

                                        TextInput::make('preparation_time')
                                            ->label(__('products.preparation_time'))
                                            ->numeric()
                                            ->minValue(0)
                                            ->suffix('min')
                                            ->helperText(__('products.preparation_time_helper')),

                                        TextInput::make('tax_rate')
                                            ->label(__('products.tax_rate'))
                                            ->numeric()
                                            ->minValue(0)
                                            ->maxValue(100)
                                            ->step(0.01)
                                            ->suffix('%')
                                            ->default(10)
                                            ->required(),

                                        TextInput::make('sort_order')
                                            ->label(__('products.sort_order'))
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make(__('products.tab_categories'))
                            ->icon('heroicon-o-tag')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Select::make('dish_types')
                                            ->label(__('products.dish_types'))
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->relationship('dishTypes', 'name')
                                            ->getOptionLabelFromRecordUsing(function (DishType $record) {
                                                return $record->getTranslation('name', app()->getLocale());
                                            })
                                            ->columnSpanFull(),

                                        Select::make('allergens')
                                            ->label(__('products.allergens'))
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->relationship('allergens', 'name')
                                            ->getOptionLabelFromRecordUsing(function (Allergen $record) {
                                                return $record->getTranslation('name', app()->getLocale());
                                            })
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('products.tab_variants'))
                            ->icon('heroicon-o-squares-2x2')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Repeater::make('variants')
                                            ->relationship('variants')
                                            ->schema([
                                                KeyValue::make('name')
                                                    ->label(__('products.variant_name'))
                                                    ->keyLabel(__('products.language'))
                                                    ->valueLabel(__('products.translation'))
                                                    ->default(['es' => '', 'ca' => '', 'en' => ''])
                                                    ->afterStateHydrated(function ($component, $state) {
                                                        $state = is_array($state) ? $state : [];
                                                        $component->state(array_merge(['es' => '', 'ca' => '', 'en' => ''], $state));
                                                    })
                                                    ->addable(false)
                                                    ->deletable(false)
                                                    ->editableKeys(false)
                                                    ->columnSpanFull(),

                                                TextInput::make('price')
                                                    ->label(__('products.variant_price'))
                                                    ->numeric()
                                                    ->prefix('€')
                                                    ->minValue(0)
                                                    ->step(0.01)
                                                    ->required(),

                                                Checkbox::make('is_default')
                                                    ->label(__('products.variant_default')),

                                                TextInput::make('sort_order')
                                                    ->label(__('products.sort_order'))
                                                    ->numeric()
                                                    ->default(0)
                                                    ->minValue(0),
                                            ])
                                            ->columns(2)
                                            ->defaultItems(0)
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                        Tabs\Tab::make(__('products.tab_config'))
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Checkbox::make('is_active')
                                            ->label(__('products.is_active'))
                                            ->default(true)
                                            ->inline(false),

                                        Checkbox::make('is_available')
                                            ->label(__('products.is_available'))
                                            ->default(true)
                                            ->inline(false),

                                        Checkbox::make('is_new')
                                            ->label(__('products.is_new'))
                                            ->default(false)
                                            ->inline(false),

                                        Checkbox::make('is_unit')
                                            ->label(__('products.is_unit'))
                                            ->default(false)
                                            ->inline(false)
                                            ->helperText(__('products.is_unit_helper')),

                                        TextInput::make('stock_quantity')
                                            ->label(__('products.stock_quantity'))
                                            ->numeric()
                                            ->minValue(0)
                                            ->helperText(__('products.stock_quantity_helper'))
                                            ->visible(fn ($get) => $get('is_unit')),
                                    ])
                                    ->columns(2),
                            ]),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo1')
                    ->label(__('products.photo1'))
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                TextColumn::make('name')
                    ->label(__('products.name'))
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn ($record) => $record->getTranslation('name', app()->getLocale())),

                TextColumn::make('price')
                    ->label(__('products.price'))
                    ->money('EUR')
                    ->sortable(),

                TextColumn::make('variants_count')
                    ->label(__('products.variants'))
                    ->counts('variants')
                    ->badge()
                    ->color('info'),

                TextColumn::make('allergens_count')
                    ->label(__('products.allergens'))
                    ->counts('allergens')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('preparation_time')
                    ->label(__('products.preparation_time'))
                    ->suffix(' min')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label(__('products.sort_order'))
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label(__('products.is_active')),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('products.is_active')),
                TernaryFilter::make('is_available')
                    ->label(__('products.is_available')),
                TernaryFilter::make('is_new')
                    ->label(__('products.is_new')),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $tenant = \Filament\Facades\Filament::getTenant();
                        $data['restaurant_id'] = $tenant ? $tenant->id : null;
                        return $data;
                    }),
                AttachAction::make()
                    ->preloadRecordSelect(),
            ])
            ->actions([
                EditAction::make(),
                DetachAction::make(),
            ])
            ->bulkActions([
                DetachBulkAction::make(),
                DeleteBulkAction::make(),
            ])
            ->defaultSort('sort_order');
    }
}
