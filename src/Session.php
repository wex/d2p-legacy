<?php
declare(strict_types=1);

namespace Wex;

use \Ramsey\Uuid\Uuid;

class Session extends \SessionHandler
{
    public function create_sid() : string
    {
        return Uuid::uuid5(Uuid::NAMESPACE_DNS, $_SERVER['HTTP_HOST'])->toString();
    }

    public static function initialize() : void
    {
        $handler = new static;
        session_set_save_handler($handler, true);
        session_start();
    }
}