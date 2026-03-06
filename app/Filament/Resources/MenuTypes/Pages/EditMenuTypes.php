<?php

namespace App\Filament\Resources\MenuTypes\Pages;

use App\Filament\Resources\MenuTypes\MenuTypesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMenuTypes extends EditRecord
{
    protected static string $resource = MenuTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
