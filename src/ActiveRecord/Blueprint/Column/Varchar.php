<?php
declare(strict_types=1);

namespace Wex\ActiveRecord\Blueprint\Column;

use \Wex\ActiveRecord\Blueprint\Column;

class Varchar extends Column
{
    public  $min    = 0;
    public  $max    = 255;

    public function getErrors($value): array
    {
        $value = "{$value}";
        $errors = parent::getErrors($value);

        if (strlen($value) < $this->min)
            $errors[] = 'underflow';

        if (strlen($value) > $this->max)
            $errors[] = 'overflow';

        return $errors;
    }
}