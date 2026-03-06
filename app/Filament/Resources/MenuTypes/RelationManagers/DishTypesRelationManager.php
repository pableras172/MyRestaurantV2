<?php

namespace App\Filament\Resources\MenuTypes\RelationManagers;

use App\Models\DishType;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DetachAction;
use Filament\Actions\DetachBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class DishTypesRelationManager extends RelationManager
{
    protected static string $relationship = 'dishTypes';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('dish_types.title');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make()
                    ->schema([
                        // Nombre
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

                        // Descripción
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
                                $tenant = \Filament\Facades\Filament::getTenant();
                                
                                if (!$tenant) {
                                    return [];
                                }
                                
                                $query = DishType::query()
                                    ->where('restaurant_id', $tenant->id)
                                    ->orderBy('sort_order');

                                // Si estamos editando, excluir el registro actual
                                if ($record) {
                                    $query->where('id', '!=', $record->id);
                                }

                                return $query->get()->mapWithKeys(function (DishType $dishType) {
                                    $name = $dishType->getTranslation('name', app()->getLocale());
                                    $prefix = '';
                                    
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

                        // Imagen
                        FileUpload::make('photo')
                            ->label(__('dish_types.photo'))
                            ->image()
                            ->disk('public')
                            ->directory(fn() => \App\Traits\HasTenantStorage::getTenantStoragePath('dish_types'))
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
                            ->helperText(__('dish_types.sort_order_helper'))
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

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
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
                    ->sortable(),

                TextColumn::make('parent.name')
                    ->label(__('dish_types.parent'))
                    ->formatStateUsing(function (DishType $record): ?string {
                        if ($record->parent) {
                            return $record->parent->getTranslation('name', app()->getLocale());
                        }
                        return '—';
                    })
                    ->toggleable(),

                TextColumn::make('products_count')
                    ->label(__('dish_types.products_count'))
                    ->counts('products')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('sort_order')
                    ->label(__('dish_types.sort_order'))
                    ->sortable(),

                ToggleColumn::make('is_active')
                    ->label(__('dish_types.is_active'))
                    ->sortable(),
            ])
            ->filters([
                //
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
