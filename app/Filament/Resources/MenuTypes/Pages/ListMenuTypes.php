<?php

namespace App\Filament\Resources\MenuTypes\Pages;

use App\Filament\Resources\MenuTypes\MenuTypesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMenuTypes extends ListRecords
{
    protected static string $resource = MenuTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
