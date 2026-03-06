<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Usuarios';

    protected static ?string $modelLabel = 'Usuario';

    protected static ?string $pluralModelLabel = 'Usuarios';

    protected static ?int $navigationSort = 2;

    // Deshabilitar tenancy para este recurso (es global)
    protected static bool $isScopedToTenant = false;

    // Solo super admin y owners pueden ver usuarios
    public static function canViewAny(): bool
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Verificar si es owner de algún restaurante
        return $user->restaurants()
            ->wherePivot('is_owner', true)
            ->exists() || 
            $user->restaurants()
            ->wherePivot('role', 'admin')
            ->exists();
    }

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canEdit($record): bool
    {
        return static::canViewAny();
    }

    public static function canDelete($record): bool
    {
        return static::canViewAny();
    }

    /**
     * Modifica el query para mostrar solo usuarios de los restaurantes del usuario logueado
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Super admin ve todos los usuarios
        if ($user->isSuperAdmin()) {
            return $query;
        }

        // Obtener IDs de restaurantes a los que tiene acceso el usuario
        $restaurantIds = $user->restaurants()->pluck('restaurants.id')->toArray();

        if (empty($restaurantIds)) {
            // Si no tiene restaurantes, solo ve su propio usuario
            return $query->where('users.id', $user->id);
        }

        // Filtrar usuarios que pertenecen a esos restaurantes O es el usuario actual
        return $query->where(function ($q) use ($restaurantIds, $user) {
            $q->whereHas('restaurants', function ($subQuery) use ($restaurantIds) {
                $subQuery->whereIn('restaurants.id', $restaurantIds);
            })
            ->orWhere('users.id', $user->id);
        });
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
