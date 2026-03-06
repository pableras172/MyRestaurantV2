<?php

namespace App\Filament\Resources\Restaurants\Schemas;

use App\Models\User;
use App\Traits\HasTenantStorage;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class RestaurantForm
{
    use HasTenantStorage;
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Información General')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                Section::make('Datos Básicos')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nombre')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                        TextInput::make('slug')
                                            ->label('Slug (URL)')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->helperText('Se usa para el subdominio: slug.myrest.com'),
                                        Textarea::make('description')
                                            ->label('Descripción')
                                            ->rows(3)
                                            ->columnSpanFull(),
                                        FileUpload::make('logo')
                                            ->label('Logo')
                                            ->image()
                                            ->disk('public')
                                            ->directory(fn() => self::getRestaurantLogoPath())
                                            ->imageEditor()
                                            ->columnSpanFull(),
                                        FileUpload::make('header_image')
                                            ->label('Imagen de Cabecera')
                                            ->image()
                                            ->disk('public')
                                            ->directory(fn() => self::getTenantStoragePath('headers'))
                                            ->imageEditor()
                                            ->helperText('Imagen que se mostrará en la cabecera de la página principal')
                                            ->columnSpanFull(),
                                        Toggle::make('is_active')
                                            ->label('Activo')
                                            ->default(true),
                                        Select::make('theme_color')
                                            ->label('Color del Tema')
                                            ->options([
                                                'color_1' => 'Color 1 - Rojo',
                                                'color_2' => 'Color 2 - Naranja',
                                                'color_3' => 'Color 3 - Verde',
                                                'color_4' => 'Color 4 - Azul',
                                                'color_5' => 'Color 5 - Rosa',
                                                'color_6' => 'Color 6 - Morado',
                                                'color_7' => 'Color 7 - Turquesa',
                                            ])
                                            ->default('color_1')
                                            ->required()
                                            ->helperText('Elige el esquema de colores para la web pública del restaurante')
                                            ->native(false),
                                        Select::make('menu_style')
                                            ->label('Estilo de Visualización del Menú')
                                            ->options([
                                                'menu-1' => 'Estilo 1 - Listado en columnas',
                                                'menu-2' => 'Estilo 2 - Grid con imágenes',
                                                'menu-3' => 'Estilo 3 - Grid destacado con precios',
                                                'menu-4' => 'Estilo 4 - Grid compacto',
                                                'menu-5' => 'Estilo 5 - Grid animado',
                                            ])
                                            ->default('menu-1')
                                            ->required()
                                            ->helperText('Selecciona cómo se mostrará el menú en la web pública')
                                            ->native(false),
                                        Select::make('default_language')
                                            ->label('Idioma por Defecto')
                                            ->options([
                                                'es' => 'Español',
                                                'ca' => 'Català',
                                                'en' => 'English',
                                            ])
                                            ->default('es')
                                            ->required()
                                            ->helperText('Idioma predeterminado para mostrar el menú en la web pública')
                                            ->native(false),
                                    ])
                                    ->columns(2),
                            ]),
                        Tabs\Tab::make('Contacto')
                            ->icon('heroicon-o-phone')
                            ->schema([
                                Section::make('Información de Contacto')
                                    ->schema([
                                        TextInput::make('address_line_1')
                                            ->label('Dirección 1')
                                            ->maxLength(255),
                                        TextInput::make('address_line_2')
                                            ->label('Dirección 2')
                                            ->maxLength(255),
                                        TextInput::make('phone_1')
                                            ->label('Teléfono 1')
                                            ->tel()
                                            ->maxLength(255),
                                        TextInput::make('phone_2')
                                            ->label('Teléfono 2')
                                            ->tel()
                                            ->maxLength(255),
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->helperText('Se enviará un correo con las credenciales del administrador al crear el restaurante'),
                                        TextInput::make('website_url')
                                            ->label('Sitio Web')
                                            ->url()
                                            ->maxLength(255),
                                    ])
                                    ->columns(2),
                            ]),
                        Tabs\Tab::make('Redes Sociales')
                            ->icon('heroicon-o-share')
                            ->schema([
                                Section::make('Perfiles en Redes')
                                    ->schema([
                                        TextInput::make('facebook_url')
                                            ->label('Facebook')
                                            ->url()
                                            ->prefix('https://')
                                            ->maxLength(255),
                                        TextInput::make('instagram_url')
                                            ->label('Instagram')
                                            ->url()
                                            ->prefix('https://')
                                            ->maxLength(255),
                                        TextInput::make('google_profile_url')
                                            ->label('Google Business')
                                            ->url()
                                            ->prefix('https://')
                                            ->maxLength(255),
                                    ])
                                    ->columns(1),
                            ]),
                        Tabs\Tab::make('Propietarios')
                            ->icon('heroicon-o-user-circle')
                            ->schema([
                                Section::make('Gestión de Propietarios')
                                    ->description('Asigna los propietarios de este restaurante. Un usuario puede ser propietario de múltiples restaurantes.')
                                    ->schema([
                                        Select::make('owner_ids')
                                            ->label('Propietarios')
                                            ->multiple()
                                            ->searchable()
                                            ->preload()
                                            ->options(function() {
                                                return User::query()
                                                    ->where('is_super_admin', false)
                                                    ->where('is_active', true)
                                                    ->pluck('name', 'id');
                                            })
                                            ->default(function ($record) {
                                                if (!$record) return [];
                                                return $record->users()
                                                    ->wherePivot('is_owner', true)
                                                    ->pluck('users.id')
                                                    ->toArray();
                                            })
                                            ->helperText('Selecciona uno o varios propietarios para este restaurante')
                                            ->saveRelationshipsUsing(function ($component, $state, $record) {
                                                // Primero, quitamos is_owner=true de todos los usuarios actuales
                                                $record->users()->updateExistingPivot(
                                                    $record->users()->pluck('users.id')->toArray(),
                                                    ['is_owner' => false]
                                                );
                                                
                                                // Luego agregamos/actualizamos los nuevos owners
                                                if ($state) {
                                                    foreach ($state as $userId) {
                                                        // Si el usuario ya está en el restaurante, actualizamos
                                                        if ($record->users()->where('users.id', $userId)->exists()) {
                                                            $record->users()->updateExistingPivot($userId, [
                                                                'is_owner' => true,
                                                                'role' => 'admin'
                                                            ]);
                                                        } else {
                                                            // Si no existe, lo agregamos
                                                            $record->users()->attach($userId, [
                                                                'is_owner' => true,
                                                                'role' => 'admin'
                                                            ]);
                                                        }
                                                    }
                                                }
                                            })
                                            ->dehydrated(false),
                                    ])
                                    ->columns(1),
                            ])
                            ->visible(fn() => auth()->user()->isSuperAdmin()),
                        Tabs\Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Section::make('Metadatos SEO')
                                    ->schema([
                                        TextInput::make('meta_title')
                                            ->label('Meta Título')
                                            ->maxLength(255),
                                        Textarea::make('meta_description')
                                            ->label('Meta Descripción')
                                            ->rows(3)
                                            ->maxLength(500),
                                        TextInput::make('meta_keywords')
                                            ->label('Palabras Clave')
                                            ->maxLength(255),
                                        TextInput::make('meta_url')
                                            ->label('URL Canónica')
                                            ->url()
                                            ->maxLength(255),
                                        FileUpload::make('meta_image')
                                            ->label('Imagen Social')
                                            ->image()
                                            ->disk('public')
                                            ->directory(fn() => self::getMetaImagesPath())
                                            ->helperText('Imagen para compartir en redes sociales'),
                                    ])
                                    ->columns(2),
                            ]),
                    ]),
            ]);
    }
}
