<?php
declare(strict_types=1);

namespace Wex\ActiveRecord\Blueprint;

abstract class Column
{
    protected   $_name;
    protected   $_unique    = false;
    protected   $_required  = true;
    protected   $_min       = null;
    protected   $_max       = null;
    protected   $_default   = null;

    public function &default(mixed $value) : self
    {
        $this->_default = $value;

        return $this;
    }

    public function &unique(bool $is = true) : self
    {
        $this->_unique = $is;

        return $this;
    }

    public function &required(bool $is = true) : self
    {
        $this->_required = $is;

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
}