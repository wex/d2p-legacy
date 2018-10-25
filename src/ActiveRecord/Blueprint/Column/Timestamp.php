<?php
declare(strict_types=1);

namespace Wex\ActiveRecord\Blueprint\Column;

use \Wex\ActiveRecord\Blueprint\Column;

class Timestamp extends Column
{
    public function __construct(string $name)
    {
        $this->_name = $name;
    }
}