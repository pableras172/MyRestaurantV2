<?php

namespace App\Filament\Resources\Restaurants;

use App\Filament\Resources\Restaurants\Pages\CreateRestaurant;
use App\Filament\Resources\Restaurants\Pages\EditRestaurant;
use App\Filament\Resources\Restaurants\Pages\ListRestaurants;
use App\Filament\Resources\Restaurants\Schemas\RestaurantForm;
use App\Filament\Resources\Restaurants\Tables\RestaurantsTable;
use App\Models\Restaurant;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RestaurantResource extends Resource
{
    protected static ?string $model = Restaurant::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Restaurantes';

    protected static ?string $modelLabel = 'Restaurante';

    protected static ?string $pluralModelLabel = 'Restaurantes';

    protected static ?int $navigationSort = 1;

    // Deshabilitar tenancy para este recurso (es global)
    protected static bool $isScopedToTenant = false;

    // Solo visible para super admin y owners
    public static function canViewAny(): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        // Super admin siempre puede
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Owners pueden ver sus restaurantes
        return $user->isOwnerOrAdmin();
    }

    // Solo super admin puede crear restaurantes
    public static function canCreate(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    // Owners pueden editar sus propios restaurantes
    public static function canEdit($record): bool
    {
        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        // Super admin puede editar cualquiera
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Verificar si el usuario es owner de este restaurante
        return $user->restaurants()
            ->where('restaurants.id', $record->id)
            ->wherePivot('is_owner', true)
            ->exists();
    }

    // Solo super admin puede eliminar restaurantes
    public static function canDelete($record): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }

    /**
     * Filtrar restaurantes según permisos del usuario
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Super admin ve todos
        if ($user->isSuperAdmin()) {
            return $query;
        }

        // Owners solo ven sus restaurantes
        $restaurantIds = $user->restaurants()
            ->wherePivot('is_owner', true)
            ->pluck('restaurants.id')
            ->toArray();

        return $query->whereIn('id', $restaurantIds);
    }

    public static function form(Schema $schema): Schema
    {
        return RestaurantForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RestaurantsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRestaurants::route('/'),
            'create' => CreateRestaurant::route('/create'),
            'edit' => EditRestaurant::route('/{record}/edit'),
        ];
    }
}
