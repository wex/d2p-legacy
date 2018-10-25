<?php
declare(strict_types=1);

namespace Wex\ActiveRecord;

use \Wex\ActiveRecord\Blueprint\Column;

class Blueprint
{
    public  $table;
    public  $pk         = 'id';
    public  $columns    = [];

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function &timestamp(string $name)
    {
        $index = count( $this->columns );
        $this->columns[ $index ] = new Column\Timestamp($name);

        return $this->columns[ $index ];
    }

    public function &string(string $name)
    {
        $index = count( $this->columns );
        $this->columns[ $index ] = new Column\Varchar($name);

        return $this->columns[ $index ];
    }

    public function &integer(string $name)
    {
        $index = count( $this->columns );
        $this->columns[ $index ] = new Column\Integer($name);

        return $this->columns[ $index ];
    }
}