<?php
declare(strict_types=1);

namespace Wex\ActiveRecord\Blueprint\Column;

use \Wex\ActiveRecord\Blueprint\Column;

class Integer extends Column
{
    public  $min    = PHP_INT_MIN;
    public  $max    = PHP_INT_MAX;
}