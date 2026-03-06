<?php

namespace App\Filament\Resources\Allergens\Schemas;

use App\Traits\HasTenantStorage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AllergensForm
{
    use HasTenantStorage;

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make(__('allergens.section_title'))
                    ->description(__('allergens.section_description'))
                    ->schema([
                        KeyValue::make('name')
                            ->label(__('allergens.name'))
                            ->keyLabel(__('allergens.language'))
                            ->valueLabel(__('allergens.translation'))
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
                            ->helperText(__('allergens.name_helper'))
                            ->columnSpanFull(),
                        
                        KeyValue::make('description')
                            ->label(__('allergens.description'))
                            ->keyLabel(__('allergens.language'))
                            ->valueLabel(__('allergens.translation'))
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
                            ->helperText(__('allergens.description_helper'))
                            ->columnSpanFull(),
                        
                        FileUpload::make('photo')
                            ->label(__('allergens.photo'))
                            ->image()
                            ->disk('public')
                            ->directory(fn() => self::getTenantStoragePath('allergens'))
                            ->imageEditor()                           
                            ->circleCropper()
                            ->maxSize(1024)
                            ->helperText(__('allergens.photo_helper'))
                            ->columnSpanFull(),
                        
                        Toggle::make('is_active')
                            ->label(__('allergens.is_active'))
                            ->default(true)
                            ->helperText(__('allergens.is_active_helper')),
                    ])
                    ->columns(2),
            ]);
    }
}
