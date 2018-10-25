<?php
declare(strict_types=1);

namespace Wex;

use \Wex\ActiveRecord\Blueprint;

abstract class ActiveRecord
{
    use ActiveRecord\Describe;

    const       table           = null;

    protected   $__data         = [];
    protected   $__blueprint    = null;

    abstract protected function describe(Blueprint &$table) : void;

    public function __get(string $key) : mixed
    {
        return $this->__data[ $key ] ?? null;
    }

    public function __set(string $key, mixed $value) : void
    {
        $this->__data[ $key ] = $value;
    }

    public static function load(string $id, bool $strict = false) : ActiveRecord
    {

    }

    public function save(bool $reload = true)
    {
        $this->__blueprint = $this->__blueprint ?? $this->bluePrint();
    }
}