<?php
declare(strict_types=1);

namespace Wex\ActiveRecord;

use \Wex\ActiveRecord\Blueprint;
use \Wex\ActiveRecord\SoftDelete;
use \Wex\ActiveRecord\Timestamps;

trait Describe {
    protected function addTimestamps()
    {

    }

    protected function addSoftDelete()
    {

    }

    protected function bluePrint() : Blueprint
    {
        $blueprint = new Blueprint( static::table );

        $this->describe($blueprint);

        if ($this instanceof SoftDelete) {
            $blueprint->timestamp('deleted_at');
        }

        if ($this instanceof Timestamps) {
            $blueprint->timestamp('created_at');
            $blueprint->timestamp('updated_at');
        }

        return $blueprint;
    }
}