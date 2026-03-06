<?php

namespace App\Filament\Resources\MenuTypes\Schemas;

use App\Traits\HasTenantStorage;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;

class MenuTypesForm
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
                        Tabs\Tab::make(__('menu_types.section_title'))
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make()
                                    ->description(__('menu_types.section_description'))
                                    ->schema([
                                        KeyValue::make('name')
                                            ->label(__('menu_types.name'))
                                            ->keyLabel(__('menu_types.language'))
                                            ->valueLabel(__('menu_types.translation'))
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
                                            ->helperText(__('menu_types.name_helper'))
                                            ->columnSpanFull(),

                                        KeyValue::make('description')
                                            ->label(__('menu_types.description'))
                                            ->keyLabel(__('menu_types.language'))
                                            ->valueLabel(__('menu_types.translation'))
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
                                            ->helperText(__('menu_types.description_helper'))
                                            ->columnSpanFull(),

                                        TextInput::make('price')
                                            ->label(__('menu_types.price'))
                                            ->numeric()
                                            ->prefix('€')
                                            ->minValue(0)
                                            ->step(0.01)
                                            ->helperText(__('menu_types.price_helper')),

                                        FileUpload::make('photo')
                                            ->label(__('menu_types.photo'))
                                            ->image()
                                            ->disk('public')
                                            ->directory(fn() => self::getTenantStoragePath('menu_types'))
                                            ->imageEditor()
                                            ->imageEditorAspectRatioOptions([
                                                '16:9',
                                                '4:3',
                                                '1:1',
                                            ])
                                            ->maxSize(1024)
                                            ->helperText(__('menu_types.photo_helper'))
                                            ->columnSpanFull(),

                                        TextInput::make('sort_order')
                                            ->label(__('menu_types.sort_order'))
                                            ->numeric()
                                            ->default(0)
                                            ->minValue(0)
                                            ->helperText(__('menu_types.sort_order_helper')),

                                        Toggle::make('is_active')
                                            ->label(__('menu_types.is_active'))
                                            ->default(true)
                                            ->helperText(__('menu_types.is_active_helper')),
                                    ])
                                    ->columns(2),
                            ]),

                        Tabs\Tab::make(__('menu_types.notes'))
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        KeyValue::make('superior_notes')
                                            ->label(__('menu_types.superior_notes'))
                                            ->keyLabel(__('menu_types.language'))
                                            ->valueLabel(__('menu_types.translation'))
                                            ->default([
                                                'es' => '',
                                                'ca' => '',
                                                'en' => '',
                                            ])
                                            ->afterStateHydrated(function ($component, $state) {
                                                // Asegurar que siempre tenga las 3 keys
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
                                            ->helperText(__('menu_types.superior_notes_helper'))
                                            ->columnSpanFull(),

                                        KeyValue::make('notes')
                                            ->label(__('menu_types.notes'))
                                            ->keyLabel(__('menu_types.language'))
                                            ->valueLabel(__('menu_types.translation'))
                                            ->default([
                                                'es' => '',
                                                'ca' => '',
                                                'en' => '',
                                            ])
                                            ->afterStateHydrated(function ($component, $state) {
                                                // Asegurar que siempre tenga las 3 keys
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
                                            ->helperText(__('menu_types.notes_helper'))
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(1),
                            ]),

                        Tabs\Tab::make('Configuración')
                            ->icon('heroicon-o-cog-6-tooth')
                            ->schema([
                                Section::make()
                                    ->schema([
                                        Checkbox::make('is_principal')
                                            ->label(__('menu_types.is_principal'))
                                            ->helperText(__('menu_types.is_principal_helper')),

                                        Checkbox::make('is_standalone')
                                            ->label(__('menu_types.is_standalone'))
                                            ->helperText(__('menu_types.is_standalone_helper')),
                                    ])
                                    ->columns(2),
                            ]),
                    ]),
            ]);
    }
}
