<?php
declare(strict_types=1);

namespace Wex\ActiveRecord\Blueprint;

abstract class Column
{
    public  $name;
    public  $unique     = false;
    public  $required   = false;
    public  $min        = null;
    public  $max        = null;
    public  $default    = null;
    public  $enum       = null;
    public  $index      = null;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function &default($value) : self
    {
        $this->default = $value;

        return $this;
    }

    public function &unique(bool $is = true) : self
    {
        $this->unique = $is;

        return $this;
    }

    public function &required(bool $is = true) : self
    {
        $this->required = $is;

        return $this;
    }

    public function &min(int $value) : self
    {
        $this->min = $value;

        return $this;
    }

    public function &max(int $value) : self
    {
        $this->max = $value;

        return $this;
    }

    public function &options(array $values) : self
    {
        $this->enum = $values;

        return $this;
    }

    public function &index(string $type = 'index') : self
    {
        $this->index = $type;

        return $this;
    }
}