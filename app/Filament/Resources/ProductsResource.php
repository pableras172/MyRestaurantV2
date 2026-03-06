<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductsResource\Pages;
use App\Filament\Resources\ProductsResource\Schemas\ProductsForm;
use App\Filament\Resources\ProductsResource\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProductsResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?string $tenantOwnershipRelationshipName = 'restaurant';

    protected static ?int $navigationSort = 13;

    public static function getNavigationGroup(): ?string
    {
        return __('Menús y Productos');
    }

    public static function getNavigationLabel(): string
    {
        return __('products.title');
    }

    public static function getModelLabel(): string
    {
        return __('products.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('products.title');
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
        return ProductsForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProducts::route('/create'),
            'edit' => Pages\EditProducts::route('/{record}/edit'),
        ];
    }
}
