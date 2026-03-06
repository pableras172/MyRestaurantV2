<?php

namespace App\Filament\Resources\DishTypesResource\Pages;

use App\Filament\Resources\DishTypesResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDishTypes extends CreateRecord
{
    protected static string $resource = DishTypesResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['restaurant_id'] = auth()->user()->restaurant_id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
