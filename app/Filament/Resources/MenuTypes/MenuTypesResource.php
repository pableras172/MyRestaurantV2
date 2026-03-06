<?php

namespace App\Filament\Resources\MenuTypes;

use App\Filament\Resources\MenuTypes\Pages\CreateMenuTypes;
use App\Filament\Resources\MenuTypes\Pages\EditMenuTypes;
use App\Filament\Resources\MenuTypes\Pages\ListMenuTypes;
use App\Filament\Resources\MenuTypes\Schemas\MenuTypesForm;
use App\Filament\Resources\MenuTypes\Tables\MenuTypesTable;
use App\Models\MenuType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MenuTypesResource extends Resource
{
    protected static ?string $model = MenuType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 11;

    public static function getNavigationGroup(): ?string
    {
        return __('Menús y Productos');
    }

    public static function getNavigationLabel(): string
    {
        return __('menu_types.title');
    }

    public static function getModelLabel(): string
    {
        return __('menu_types.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('menu_types.title');
    }

    // Activar tenancy - solo para el restaurante actual
    protected static bool $isScopedToTenant = true;

    // Definir la relación de ownership con el tenant
    protected static ?string $tenantOwnershipRelationshipName = 'restaurant';

    // Solo admin y managers pueden gestionar tipos de menú
    public static function canViewAny(): bool
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Owners, admins y managers pueden gestionar tipos de menú
        return $user->restaurants()->wherePivot('is_owner', true)->exists() ||
               $user->restaurants()->whereIn('restaurant_user.role', ['admin', 'manager'])->exists();
    }

    public static function form(Schema $schema): Schema
    {
        return MenuTypesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MenuTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\DishTypesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMenuTypes::route('/'),
            'create' => CreateMenuTypes::route('/create'),
            'edit' => EditMenuTypes::route('/{record}/edit'),
        ];
    }
}
