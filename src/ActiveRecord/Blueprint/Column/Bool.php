<?php
declare(strict_types=1);

namespace Wex\ActiveRecord\Blueprint\Column;

use \Wex\ActiveRecord\Blueprint\Column;

class Boolean extends Column
{
    public  $min = 0;
    public  $max = 1;

    public function getErrors($value): array
    {
        $value = $value ? 1 : 0;
        $errors = parent::getErrors($value);

        if ($value < $this->min)
            $errors[] = 'underflow';

        if ($value > $this->max)
            $errors[] = 'overflow';

        return $errors;
    }

    public function setValue($value)
    {
        return $value ? 1 : 0;
    }
}