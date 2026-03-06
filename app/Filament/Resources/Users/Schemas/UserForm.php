<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Traits\HasTenantStorage;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    use HasTenantStorage;

    /**
     * Filtra los restaurantes según los permisos del usuario logueado
     */
    protected static function getAvailableRestaurantsQuery($query)
    {
        $user = auth()->user();

        // Super admin ve todos los restaurantes
        if ($user->isSuperAdmin()) {
            return $query;
        }

        // Owners/Admins solo ven sus restaurantes
        $restaurantIds = $user->restaurants()->pluck('restaurants.id')->toArray();
        return $query->whereIn('restaurants.id', $restaurantIds);
    }
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Información Personal')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Section::make('Datos del Usuario')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Nombre Completo')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255),
                                        TextInput::make('password')
                                            ->label('Contraseña')
                                            ->password()
                                            ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                                            ->dehydrated(fn ($state) => filled($state))
                                            ->required(fn (string $context): bool => $context === 'create')
                                            ->maxLength(255)
                                            ->helperText('Deja en blanco para mantener la contraseña actual'),
                                        FileUpload::make('photo')
                                            ->label('Foto de Perfil')
                                            ->image()
                                            ->disk('public')
                                            ->directory(fn () => static::getUserPhotosPath())
                                            ->imageEditor()
                                            ->avatar()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2),
                            ]),
                        Tabs\Tab::make('Permisos y Acceso')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Section::make('Configuración de Acceso')
                                    ->schema([
                                        Toggle::make('is_super_admin')
                                            ->label('Super Administrador')
                                            ->helperText('Acceso total a todos los restaurantes')
                                            ->default(false)
                                            ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
                                        Toggle::make('is_active')
                                            ->label('Usuario Activo')
                                            ->helperText('Permitir acceso al sistema')
                                            ->default(true),
                                        DateTimePicker::make('email_verified_at')
                                            ->label('Email Verificado')
                                            ->helperText('Fecha de verificación del email'),
                                    ])
                                    ->columns(1),
                            ]),
                        Tabs\Tab::make('Restaurantes')
                            ->icon('heroicon-o-building-storefront')
                            ->schema([
                                Section::make('Asignación de Restaurantes')
                                    ->description('Gestiona los restaurantes y roles del usuario')
                                    ->schema([
                                        Select::make('restaurants')
                                            ->label('Restaurantes Asignados')
                                            ->relationship(
                                                name: 'restaurants',
                                                titleAttribute: 'name',
                                                modifyQueryUsing: fn ($query) => static::getAvailableRestaurantsQuery($query)
                                            )
                                            ->multiple()
                                            ->preload()
                                            ->searchable()
                                            ->helperText('Selecciona los restaurantes a los que tendrá acceso este usuario')
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->hidden(function () {
                                $user = auth()->user();
                                if (!$user) {
                                    return true;
                                }
                                // Ocultar si no es super admin ni owner/admin
                                return !$user->isSuperAdmin() && !$user->isOwnerOrAdmin();
                            }),
                    ]),
            ]);
    }
}
