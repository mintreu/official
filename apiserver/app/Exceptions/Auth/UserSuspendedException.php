<?php
declare(strict_types=1);

namespace App\Exceptions\Auth;

use Exception;

class UserSuspendedException extends Exception
{
    protected  = 'Account is suspended temporarily.';
    protected  = 403;
}

