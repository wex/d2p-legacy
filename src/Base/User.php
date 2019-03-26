<?php
declare(strict_types=1);

namespace Wex\Base;

use Wex\ActiveRecord;
use Wex\ActiveRecord\Blueprint;
use Wex\ActiveRecord\Exception\NotFound;
use Wex\App\UserException;
use Wex\ActiveRecord\Timestamps;

abstract class User extends ActiveRecord implements Timestamps
{
    const table = 'users';

    protected function describe(Blueprint &$blueprint): void
    {
        $blueprint->string('username')->unique()->required()->index('unique');
        $blueprint->string('password');
        $blueprint->string('state')->options(['pending', 'active', 'banned'])->default('pending');
    }

    public function __set(string $key, $value)
    {
        switch ($key) {
            case 'password':
                $value = static::hash($value);
                break;
        }

        parent::__set($key, $value);
    }

    public static function logged() : bool
    {
        return static::current() instanceof User;
    }

    public static function current()
    {
        return $_SESSION['user'] ?? null;
    }

    public static function login($username, $password) : bool
    {
        $instance = static::select()->where('username = ?', "{$username}")->first();

        if (!password_verify($password, $instance->password))
            throw new UserException("Invalid password");

        if (!in_array($instance->state, ['active']))
            throw new UserException("Not active");

        $_SESSION['user'] = $instance;

        return true;
    }
    
    public static function logout()
    {
        unset( $_SESSION['user'] );
    }

    public static function hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}
