<?php

namespace App\Filament\Resources\BodyResource\Pages;

use App\Filament\Resources\BodyResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBody extends CreateRecord
{
    protected static string $resource = BodyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'The body was created!';
    }
}
