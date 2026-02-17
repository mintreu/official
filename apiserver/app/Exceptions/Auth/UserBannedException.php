<?php
declare(strict_types=1);

namespace App\Exceptions\Auth;

use Exception;

class UserBannedException extends Exception
{
    protected  = 'Account is banned.';
    protected  = 403;
}

