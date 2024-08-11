<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BodiesTypeEnum: string implements HasLabel
{
    case Planet = 'planet';
    case Asteroid = 'asteroid';
    case Moon = 'moon';
    case Star = 'star';
    case Comet = 'comet';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Planet => 'planet',
            self::Asteroid => 'asteroid',
            self::Moon => 'moon',
            self::Star => 'star',
            self::Comet => 'comet'
        };
    }
}
