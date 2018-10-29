<?php
declare(strict_types=1);

namespace Wex\ActiveRecord\Blueprint\Column;

use \Wex\ActiveRecord\Blueprint\Column;
use Wex\ActiveRecord\Select;

class HasMany extends Column
{
    public $class   = null;
    public $key     = null;

    public function getErrors($value): array
    {
        return [];
    }

    public function setClass(string $class) : void
    {
        $this->class = $class;
    }

    public function setForeignKey(string $name) : void
    {
        $this->key  = $name;
    }

    public function select($key) : Select
    {
        $select = $this->class::select();
        $select->where([$this->key => $key]);

        return $select;
    }
}