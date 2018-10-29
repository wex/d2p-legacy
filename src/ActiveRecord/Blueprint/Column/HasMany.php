<?php
declare(strict_types=1);

namespace Wex\ActiveRecord\Blueprint\Column;

use \Wex\ActiveRecord\Blueprint\Column;

class HasMany extends Column
{
    public $class = null;

    public function getErrors($value): array
    {
        return [];
    }

    public function setClass(string $class) : void
    {
        $this->class = $class;
    }
}