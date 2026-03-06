<?php

namespace App\Filament\Resources\DishTypesResource\Pages;

use App\Filament\Resources\DishTypesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDishTypes extends EditRecord
{
    protected static string $resource = DishTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
