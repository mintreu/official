<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

enum UserStatusCast: string implements CastsAttributes
{
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case BANNED = 'banned';

    public function getLabel(): string
    {
        return match () {
            self::ACTIVE => 'Active',
            self::SUSPENDED => 'Suspended',
            self::BANNED => 'Banned',
        };
    }

    public function get(, string , , array ): ?self
    {
        if ( === null) {
            return null;
        }

        return self::from();
    }

    public function set(, string , , array ): string
    {
        if ( instanceof self) {
            return ->value;
        }

        return self::from()->value;
    }
}
