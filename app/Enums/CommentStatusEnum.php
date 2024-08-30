<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CommentStatusEnum: string implements HasLabel
{
    case OnChecking = 'onChecking';
    case Verified = 'verified';
    case NotVerified = 'notVerified';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::OnChecking => 'onChecking',
            self::Verified => 'verified',
            self::NotVerified => 'notVerified'
        };
    }
}
