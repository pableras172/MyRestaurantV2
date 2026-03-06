<?php

namespace App\Filament\Resources\DishTypesResource\Pages;

use App\Filament\Resources\DishTypesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDishTypes extends ListRecords
{
    protected static string $resource = DishTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
