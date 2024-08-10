<?php

namespace App\Filament\Resources\GalaxyResource\Pages;

use App\Filament\Resources\GalaxyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGalaxy extends CreateRecord
{
    protected static string $resource = GalaxyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'The galaxy was created!';
    }
}
