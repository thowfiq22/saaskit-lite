<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(
            static fn (self $role): string => $role->value,
            self::cases()
        );
    }
}
