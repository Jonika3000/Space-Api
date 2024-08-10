<?php

namespace App\Filament\Resources\BodyResource\Pages;

use App\Filament\Resources\BodyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBody extends EditRecord
{
    protected static string $resource = BodyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
