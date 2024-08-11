<?php

namespace App\Filament\Resources\BodyResource\Pages;

use App\Filament\Resources\BodyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBodies extends ListRecords
{
    protected static string $resource = BodyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
