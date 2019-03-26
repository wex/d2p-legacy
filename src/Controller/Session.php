<?php
declare(strict_types=1);

namespace Wex\Controller;

use Wex\App;

class Session
{
    public function __set(string $key, $value) : void
    {
        $_SESSION[$key] = $value;
    }

    public function __get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function __isset(string $key) : bool
    {
        return isset($_SESSION[$key]);
    }

    public function __unset(string $key)
    {
        unset( $_SESSION[$key] );
    }
}