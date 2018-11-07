<?php
declare(strict_types=1);

namespace Wex\Base;

use Wex\ActiveRecord;

abstract class User extends ActiveRecord
{
    const table = 'users';

    public static function logged() : bool
    {
        return static::current() instanceof User;
    }

    public static function current()
    {
        return $_SESSION['user'] ?? null;
    }
}
