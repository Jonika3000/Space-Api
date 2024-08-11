<?php

namespace App\Filament\Resources\GalaxyResource\Pages;

use App\Filament\Resources\GalaxyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGalaxy extends EditRecord
{
    protected static string $resource = GalaxyResource::class;

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
    protected function getSavedNotificationTitle(): ?string
    {
        return 'The galaxy was updated!';
    }
}
