<?php
declare(strict_types=1);

namespace Wex;

use \Ramsey\Uuid\Uuid;

class Session extends \SessionHandler
{
    protected   $key;

    public function __construct(string $key = null)
    {
        if (is_null($key) || strlen($key) < 20) throw new \ErrorException("Misconfigured application key.");
        
        $this->key = $key;
    }

    public function create_sid() : string
    {
        return Uuid::uuid5(Uuid::NAMESPACE_DNS, $_SERVER['HTTP_HOST'])->toString();
    }

    public static function initialize(string $key) : void
    {
        $handler = new static($key);
        session_set_save_handler($handler, true);
        session_start();
    }
}