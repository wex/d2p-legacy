<?php
declare(strict_types=1);

namespace Wex\ActiveRecord\Blueprint\Column;

use \Wex\ActiveRecord\Blueprint\Column;

class Varchar extends Column
{
    protected   $_min = 0;
    protected   $_max = 255;

    public function __construct(string $name)
    {
        $this->_name = $name;
    }
}