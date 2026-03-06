<?php

namespace App\Filament\Resources\Allergens;

use App\Filament\Resources\Allergens\Pages\CreateAllergens;
use App\Filament\Resources\Allergens\Pages\EditAllergens;
use App\Filament\Resources\Allergens\Pages\ListAllergens;
use App\Filament\Resources\Allergens\Schemas\AllergensForm;
use App\Filament\Resources\Allergens\Tables\AllergensTable;
use App\Models\Allergen;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AllergensResource extends Resource
{
    protected static ?string $model = Allergen::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Alérgenos';

    protected static ?string $modelLabel = 'Alérgeno';

    protected static ?string $pluralModelLabel = 'Alérgenos';

    public static function getNavigationLabel(): string
    {
        return __('allergens.title');
    }

    public static function getModelLabel(): string
    {
        return __('allergens.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('allergens.title');
    }

    protected static ?int $navigationSort = 10;

    public static function getNavigationGroup(): ?string
    {
        return __('Menús y Productos');
    }

    // Activar tenancy - solo para el restaurante actual
    protected static bool $isScopedToTenant = true;

    // Definir la relación de ownership con el tenant
    protected static ?string $tenantOwnershipRelationshipName = 'restaurant';

    // Solo admin y managers pueden gestionar alérgenos
    public static function canViewAny(): bool
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Owners, admins y managers pueden gestionar alérgenos
        // Verificamos si es owner O tiene rol admin/manager
        return $user->restaurants()->wherePivot('is_owner', true)->exists() ||
               $user->restaurants()->whereIn('restaurant_user.role', ['admin', 'manager'])->exists();
    }

    public static function form(Schema $schema): Schema
    {
        return AllergensForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AllergensTable::configure($table);
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
            'index' => ListAllergens::route('/'),
            'create' => CreateAllergens::route('/create'),
            'edit' => EditAllergens::route('/{record}/edit'),
        ];
    }
}
