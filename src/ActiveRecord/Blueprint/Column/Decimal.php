<?php
declare(strict_types=1);

namespace Wex\ActiveRecord\Blueprint\Column;

use \Wex\ActiveRecord\Blueprint\Column;

class Decimal extends Column
{
    // Default format (10,4)
    public  $min    = 4;
    public  $max    = 10;
}