<?php
declare(strict_types=1);

namespace Wex\ActiveRecord\Blueprint\Column;

use \Wex\ActiveRecord\Blueprint\Column;

class Decimal extends Column
{
    public  $min    = PHP_INT_MIN;
    public  $max    = PHP_INT_MAX;

    public function getErrors($value): array
    {
        $value = floatval($value);
        $errors = parent::getErrors($value);

        if ($value < $this->min)
            $errors[] = 'underflow';

        if ($value > $this->max)
            $errors[] = 'overflow';

        return $errors;
    }

    public function setValue($value)
    {
        return floatval($value);
    }
}