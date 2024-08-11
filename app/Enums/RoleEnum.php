<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case EDITOR = 'editor';
    case USER = 'user';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ADMIN => 'admin',
            self::EDITOR => 'editor',
            self::USER => 'user'
        };
    }
}
