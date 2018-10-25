<?php
declare(strict_types=1);

namespace Wex;

use \Wex\ActiveRecord\Blueprint;
use \Wex\ActiveRecord\Select;
use \Wex\ActiveRecord\Exception\NotFound;

abstract class ActiveRecord
{
    use ActiveRecord\Describe;
    use ActiveRecord\Harry;

    const       table           = null;

    protected   $__dirty        = [];
    protected   $__data         = [];
    protected   $__blueprint    = null;

    abstract protected function describe(Blueprint &$table) : void;

    public function __construct(array $values = [])
    {
        $this->__data = $values;
    }

    public function __get(string $key)
    {
        return $this->__data[ $key ] ?? null;
    }

    public function __set(string $key, $value) : void
    {
        if (!isset($this->__dirty[ $key ])) {
            $this->__dirty[ $key ] = $this->__data[ $key ];
        }
        $this->__data[ $key ] = $value;
    }

    public static function load(string $id, bool $strict = true)
    {
        try {
            return static::select()->where('id = ?', $id)->first();
        } catch (NotFound $e) {
            if ($strict) throw $e;
            return false;
        }
    }

    public function save(bool $reload = true)
    {
        $this->__blueprint = $this->__blueprint ?? $this->bluePrint();
    }

    public static function select() : Select
    {
        return new Select(static::class);
    }

    public static function create($data) : self
    {
        if (!is_array($data)) {
            throw new NotFound;
        } else {
            return new static($data);
        }
    }
}