<?php
declare(strict_types=1);

namespace Wex\ActiveRecord\Blueprint\Column;

use \Wex\ActiveRecord\Blueprint\Column;

class Varchar extends Column
{
    public  $min    = 0;
    public  $max    = 255;
}