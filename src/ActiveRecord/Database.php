<?php
declare(strict_types=1);

namespace Wex\ActiveRecord;

use Wex\ActiveRecord\Timestamps;

trait Database 
{
    protected function _insert(array $data)
    {
        if ($this instanceof Timestamps) {
            $data['created_at'] = static::now();        
        }

        print_r(['insert', $data]);
    }

    protected function _update(array $data, $id)
    {
        if ($this instanceof Timestamps) {
            $data['updated_at'] = static::now();
        }
        print_r(['update', $data, $id]);
    }

    public static function now(string $format = 'Y-m-d H:i:s')
    {
        return date($format);
    }

}