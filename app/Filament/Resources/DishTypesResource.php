<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DishTypesResource\Pages;
use App\Filament\Resources\DishTypesResource\Schemas\DishTypesForm;
use App\Filament\Resources\DishTypesResource\Tables\DishTypesTable;
use App\Models\DishType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DishTypesResource extends Resource
{
    protected static ?string $model = DishType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $tenantOwnershipRelationshipName = 'restaurant';

    protected static ?int $navigationSort = 12;

    public static function getNavigationGroup(): ?string
    {
        return __('Menús y Productos');
    }

    public static function getNavigationLabel(): string
    {
        return __('dish_types.title');
    }

    public static function getModelLabel(): string
    {
        return __('dish_types.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('dish_types.title');
    }

    protected static bool $isScopedToTenant = true;

    public static function canViewAny(): bool
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->restaurants()->wherePivot('is_owner', true)->exists() ||
               $user->restaurants()->whereIn('restaurant_user.role', ['admin', 'manager'])->exists();
    }

    public static function form(Schema $schema): Schema
    {
        return DishTypesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DishTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DishTypesResource\RelationManagers\ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDishTypes::route('/'),
            'create' => Pages\CreateDishTypes::route('/create'),
            'edit' => Pages\EditDishTypes::route('/{record}/edit'),
        ];
    }
}
