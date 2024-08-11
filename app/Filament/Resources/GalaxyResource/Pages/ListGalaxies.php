<?php

namespace App\Filament\Resources\GalaxyResource\Pages;

use App\Filament\Resources\GalaxyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGalaxies extends ListRecords
{
    protected static string $resource = GalaxyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
